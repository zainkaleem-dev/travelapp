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
        'livewire.update', // Handled specially inside handle()
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
        'livewire/update',
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
            $livewireAction = $this->resolveLivewireAction($request);

            // No recognisable action found (e.g. background polling with no changes) → skip.
            if ($livewireAction === null) {
                return $response;
            }
        }

        // ── 6. Store the log ──────────────────────────────────────────────────
        try {
            $afterState = $this->resolveAfterState($trackedModel);

            ActivityLogHelper::storeFromRequest(
                $request,
                [
                    'status_code'      => $response->getStatusCode(),
                    '_livewire_action' => $livewireAction,
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
    private function resolveLivewireAction(Request $request): ?string
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
                foreach (self::LIVEWIRE_METHOD_MAP as $keyword => $action) {
                    if (str_contains($method, $keyword)) {
                        return $action;
                    }
                }
            }

            // If there's a method call but no specific CRUD keyword matched,
            // log it as a generic "performed" action (capturing "every click").
            return 'performed';
        }

        // If there are property updates (but no matched call) treat as updated.
        $updates = $component['updates'] ?? [];
        if (is_array($updates) && !empty($updates)) {
            return 'updated';
        }

        // Nothing actionable found.
        return null;
    }

    /**
     * Finds the first route-model-binding parameter from the request.
     */
    private function resolveTrackedModel(Request $request): ?Model
    {
        $route = $request->route();
        if (!$route || !method_exists($route, 'parameters')) {
            return null;
        }

        foreach ($route->parameters() as $parameter) {
            if ($parameter instanceof Model) {
                return $parameter;
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