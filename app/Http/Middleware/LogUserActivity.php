<?php

namespace App\Http\Middleware;

use App\Helpers\ActivityLogHelper;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * HTTP methods that represent metadata requests — never logged.
     */
    private const SKIP_METHODS = ['HEAD', 'OPTIONS'];

    /**
     * Route names or patterns that should never be logged (noise).
     */
    private const SKIP_ROUTES = [
        // 'livewire.update', // UN-SKIPPED to capture actions
        'livewire.js',
        'livewire.preview',
        'up', // Health check
        'sanctum.csrf-cookie',
        'ignition.*',
        'debugbar.*',
    ];

    /**
     * URL path fragments that should never be logged (noise).
     */
    private const SKIP_PATHS = [
        'livewire/livewire.js',
        // 'livewire/update', // UN-SKIPPED to capture actions
        '_debugbar',
        'telescope',
        'horizon',
    ];

    /**
     * Livewire call-method keywords that map to a CRUD action.
     * Checked against the first component's call method name (case-insensitive).
     */
    private const LIVEWIRE_METHOD_MAP = [
        // delete
        'delete'  => 'deleted',
        'destroy' => 'deleted',
        'remove'  => 'deleted',
        // create / store
        'save'    => 'created',
        'store'   => 'created',
        'create'  => 'created',
        // update
        'update'  => 'updated',
        'edit'    => 'updated',
        // toggle
        'toggle'  => 'updated',
        // generic
        'add'     => 'created',
        'submit'  => 'created',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // ── 1. Capture before-state before the request is processed ──────────
        //       Only attempt state tracking for data-changing methods.
        $trackedModel = null;
        $beforeState  = null;

        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH') || $request->isMethod('DELETE')) {
            $trackedModel = $this->resolveTrackedModel($request);
            $beforeState  = $trackedModel?->attributesToArray();
        }

        $response = $next($request);

        // ── 2. Skip unauthenticated requests ─────────────────────────────────
        if (!auth()->check()) {
            return $response;
        }

        // ── 3. Skip internal / noise requests ────────────────────────────────
        if ($this->shouldSkip($request)) {
            return $response;
        }

        // ── 4. Skip read-only HTTP verbs (except GET which we now log) ────────
        if (in_array($request->method(), self::SKIP_METHODS, true)) {
            return $response;
        }

        // ── 5. For Livewire POST requests, only log meaningful actions ────────
        $livewireAction = null;
        if ($this->isLivewireRequest($request)) {
            $livewireAction = $this->resolveLivewireAction($request, $trackedModel);

            // No recognisable action found (e.g. background polling with no changes) → skip.
            if ($livewireAction === null) {
                return $response;
            }
        }

        // ── 6. Store the log ──────────────────────────────────────────────────
        try {
            $afterState = $this->resolveAfterState($trackedModel);
            
            // Capture a friendly name for the affected record (if any)
            $subjectName = null;
            if ($trackedModel) {
                $subjectName = $trackedModel->name 
                    ?? $trackedModel->display_name 
                    ?? $trackedModel->company_name 
                    ?? $trackedModel->label 
                    ?? $trackedModel->title;
            }

            // For Livewire requests, if the tracked model is null (e.g. generic listing/update),
            // try to pull the entity name from the snapshot data (e.g. $this->company_name).
            if (!$subjectName && $this->isLivewireRequest($request)) {
                $components = $request->input('components', []);
                $snapshotRaw = $components[0]['snapshot'] ?? null;
                if ($snapshotRaw) {
                    $snapshot = json_decode($snapshotRaw, true);
                    $data = $snapshot['data'] ?? [];
                    $subjectName = $data['company_name'] ?? $data['name'] ?? $data['title'] ?? $data['label'] ?? null;
                    
                    // If it's a model array [id, type], we can't easily get the name here,
                    // but we'll try to find any string property that looks like a name.
                }
            }

            ActivityLogHelper::storeFromRequest(
                $request,
                [
                    'status_code'      => $response->getStatusCode(),
                    '_livewire_action' => $livewireAction,
                    'subject_name'     => $subjectName,
                ],
                $beforeState,
                $afterState,
                $livewireAction
            );
        } catch (\Throwable) {
            // Logging must never break the user flow.
        }

        return $response;
    }

    /**
     * Determines if the request should be ignored for audit logging.
     */
    private function shouldSkip(Request $request): bool
    {
        $routeName = $request->route()?->getName();
        $path      = $request->path();

        // Check exact or wildcard route matches.
        foreach (self::SKIP_ROUTES as $skipRoute) {
            if ($routeName === $skipRoute || ($routeName && str_contains($skipRoute, '*') && \Illuminate\Support\Str::is($skipRoute, $routeName))) {
                return true;
            }
        }

        // Check path fragments.
        foreach (self::SKIP_PATHS as $skipPath) {
            if (str_contains($path, $skipPath)) {
                return true;
            }
        }

        return false;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Returns true when the request is a Livewire component update.
     */
    private function isLivewireRequest(Request $request): bool
    {
        return $request->route()?->getName() === 'livewire.update'
            || str_contains($request->path(), 'livewire/update');
    }

    /**
     * Inspects the Livewire payload and returns the resolved CRUD action name,
     * or null when no recognisable action is found (e.g. property sync only).
     */
    private function resolveLivewireAction(Request $request, ?Model $trackedModel = null): ?string
    {
        $components = $request->input('components', []);
        $component  = $components[0] ?? null;

        if (!is_array($component)) {
            return null;
        }

        // Check explicit method calls first (most reliable signal).
        $calls = $component['calls'] ?? [];
        if (is_array($calls) && !empty($calls)) {
            foreach ($calls as $call) {
                $method = strtolower((string) ($call['method'] ?? ''));

                // Special handling for generic methods that could be either create or update.
                // If we have a tracked model that already exists, it's an update.
                if (str_contains($method, 'save') || str_contains($method, 'submit')) {
                    return ($trackedModel && $trackedModel->exists) ? 'updated' : 'created';
                }

                foreach (self::LIVEWIRE_METHOD_MAP as $keyword => $action) {
                    if (str_contains($method, $keyword)) {
                        return $action;
                    }
                }
            }

            // If there's a method call but no specific CRUD keyword matched,
            // log it as a generic "performed" action.
            return 'performed';
        }

        // We NO LONGER log property updates (without a call) to avoid noise from filters/keystrokes.
        return null;
    }

    /**
     * Finds the first route-model-binding parameter from the request.
     * For Livewire requests, it attempts to resolve the model from method call arguments.
     */
    private function resolveTrackedModel(Request $request): ?Model
    {
        $route = $request->route();
        
        // 1. Standard route-model binding (works for standard pages and Edit pages)
        if ($route && method_exists($route, 'parameters')) {
            foreach ($route->parameters() as $parameter) {
                if ($parameter instanceof Model) {
                    return $parameter;
                }
            }
        }

        // 2. Heuristic: Resolve model from Livewire call arguments (works for Listings)
        if ($this->isLivewireRequest($request)) {
            $components = $request->input('components', []);
            $component  = $components[0] ?? null;
            $calls      = $component['calls'] ?? [];

            foreach ($calls as $call) {
                $params = $call['params'] ?? [];
                
                // Find the first numeric parameter that could be an ID
                $id = null;
                foreach ($params as $param) {
                    if (is_numeric($param)) {
                        $id = $param;
                        break;
                    }
                }

                if ($id) {
                    $compName   = strtolower($component['name'] ?? '');
                    $modelClass = match (true) {
                        str_contains($compName, 'company') => \App\Models\Company::class,
                        str_contains($compName, 'branch')  => \App\Models\Branch::class,
                        str_contains($compName, 'user')    => \App\Models\User::class,
                        str_contains($compName, 'country') => \App\Models\Country::class,
                        str_contains($compName, 'city')    => \App\Models\City::class,
                        default => null,
                    };

                    if ($modelClass) {
                        try {
                            // We use withoutGlobalScopes() and withTrashed() to ensure we find the record
                            // even if it was just soft-deleted or has scoping applied.
                            return $modelClass::withoutGlobalScopes()->withTrashed()->find($id);
                        } catch (\Throwable) {
                            continue;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Re-fetches the tracked model after the request to capture its new state.
     * Returns null if the model was deleted.
     */
    private function resolveAfterState(?Model $trackedModel): ?array
    {
        if (!$trackedModel) {
            return null;
        }

        $fresh = $trackedModel->fresh();

        return $fresh?->attributesToArray();
    }
}