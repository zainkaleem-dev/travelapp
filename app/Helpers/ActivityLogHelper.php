<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogHelper
{
    /**
     * Store a JSON-friendly activity log record.
     *
     * NOTE: $beforeState / $afterState are stored explicitly in the activity
     * JSON so the model accessors can surface them without guesswork.
     */
    public static function storeJson(
        array $activity,
        ?int $companyId = null,
        ?int $branchId = null,
        ?int $userId = null,
        ?string $page = null,
        ?string $actionName = null,
        ?array $beforeState = null,
        ?array $afterState = null
    ): ActivityLog {
        $user        = Auth::user();
        $isSuperAdmin = $user && $user->can('Manage Global System');

        // Always store before/after state keys so the model accessors never
        // have to fall through to the noisy Livewire snapshot heuristics for
        // records created through this helper.
        $activity['before_state'] = $beforeState;
        $activity['after_state']  = $afterState;

        return ActivityLog::query()->create([
            // Super-admin logs retain the actor's identity — previously user_id
            // was set to null for super-admins which lost all actor information.
            'company_id'  => $isSuperAdmin ? null : $companyId,
            'branch_id'   => $isSuperAdmin ? null : $branchId,
            'user_id'     => $userId ?? Auth::id(),   // ← always capture actor
            'activity'    => $activity,
            'page'        => $page,
            'action_name' => $actionName,
        ]);
    }

    /**
     * Build and persist a log entry from an HTTP request context.
     *
     * @param string|null $overrideAction  Pass the resolved CRUD action (e.g. from
     *                                     Livewire call introspection) to override
     *                                     the HTTP-verb-based default.
     */
    public static function storeFromRequest(
        Request $request,
        array $activity = [],
        ?array $beforeState = null,
        ?array $afterState = null,
        ?string $overrideAction = null
    ): ActivityLog {
        $user = Auth::user();

        $route       = $request->route();
        $routeName   = $route?->getName();
        $routeParams = method_exists($route, 'parameters') ? $route->parameters() : [];

        $actorRole = $user?->can('Manage Global System')
            ? 'Super Admin'
            : ($user?->getRoleNames()?->first() ?? null);

        $actorName = $user?->name
            ?? trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? ''))
            ?: null;

        // Strip sensitive fields from the logged input.
        $requestInput = $request->except([
            'password',
            'password_confirmation',
            'current_password',
            '_token',
        ]);

        foreach ($requestInput as $key => $value) {
            if (is_object($value)) {
                $requestInput[$key] = method_exists($value, 'getClientOriginalName')
                    ? $value->getClientOriginalName()
                    : get_class($value);
            }
        }

        // ── Resolve page label ────────────────────────────────────────────────
        // For Livewire requests the route name is always "livewire.update" and
        // the meaningful page is in the Referer header.
        $isLivewire = $routeName === 'livewire.update'
            || str_contains($request->path(), 'livewire/update');

        if ($isLivewire) {
            $referer = $request->headers->get('referer');
            $page    = $referer
                ? (parse_url($referer, PHP_URL_PATH) ?: '/' . ltrim($request->path(), '/'))
                : '/' . ltrim($request->path(), '/');
        } else {
            $page = $routeName ?: '/' . ltrim($request->path(), '/');
        }

        // ── Resolve action name ───────────────────────────────────────────────
        // Prefer an explicitly supplied override (e.g. from Livewire call
        // introspection) over the HTTP-verb heuristic.
        $actionName = $overrideAction ?? self::resolveActionName($request->method());

        // For 'created' actions the before-state must always be null so the
        // detail view never shows phantom "before" data.
        if ($actionName === 'created') {
            $beforeState = null;
        }

        // For 'deleted' actions the after-state must always be null.
        if ($actionName === 'deleted') {
            $afterState = null;
        }

        return self::storeJson(
            array_merge([
                'method'         => $request->method(),
                'route_name'     => $routeName,
                'path'           => $request->path(),
                'url'            => $request->fullUrl(),
                'route_params'   => $routeParams,
                'input'          => $requestInput,
                'actor_id'       => $user?->id,
                'actor_name'     => $actorName,
                'actor_role'     => $actorRole,
                'performed_at'   => now()->toDateTimeString(),
            ], $activity),
            $user?->company_id,
            $user?->branch_id,
            $user?->id,
            $page,
            $actionName,
            $beforeState,
            $afterState
        );
    }

    /**
     * Derive a basic action name from an HTTP verb.
     * This is a fallback — prefer passing an $overrideAction when the calling
     * context has more precise information (e.g. Livewire call method names).
     */
    private static function resolveActionName(string $method): string
    {
        return match (strtoupper($method)) {
            'GET'            => 'viewed',
            'POST'           => 'created',
            'PUT', 'PATCH'   => 'updated',
            'DELETE'         => 'deleted',
            default          => 'performed',
        };
    }
}