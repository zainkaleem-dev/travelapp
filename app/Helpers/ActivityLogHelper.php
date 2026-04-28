<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogHelper
{
    /**
     * Store a JSON-friendly activity log record.
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
        $user = Auth::user();
        $isSuperAdmin = $user && $user->can('Manage Global System');

        return ActivityLog::query()->create([
            'company_id' => $isSuperAdmin ? null : $companyId,
            'branch_id' => $isSuperAdmin ? null : $branchId,
            'user_id' => $isSuperAdmin ? null : ($userId ?? Auth::id()),
            'activity' => $activity,
            'page' => $page,
            'action_name' => $actionName,
            'before_state' => $beforeState,
            'after_state' => $afterState,
        ]);
    }

    public static function storeFromRequest(
        Request $request,
        array $activity = [],
        ?array $beforeState = null,
        ?array $afterState = null
    ): ActivityLog
    {
        $user = Auth::user();

        $route = $request->route();
        $routeName = $route?->getName();
        $routeParams = method_exists($route, 'parameters') ? $route->parameters() : [];
        $actorRole = $user?->can('Manage Global System')
            ? 'Super Admin'
            : ($user?->getRoleNames()?->first() ?? null);
        $actorName = $user?->name
            ?? trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? ''))
            ?: null;
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

        $page = $routeName ?: '/' . ltrim($request->path(), '/');
        $actionName = self::resolveActionName($request->method());

        return self::storeJson(
            array_merge([
                'method' => $request->method(),
                'route_name' => $routeName,
                'path' => $request->path(),
                'url' => $request->fullUrl(),
                'route_params' => $routeParams,
                'input' => $requestInput,
                'actor_id' => $user?->id,
                'actor_name' => $actorName,
                'actor_role' => $actorRole,
                'performed_at' => now()->toDateTimeString(),
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

    private static function resolveActionName(string $method): string
    {
        return match (strtoupper($method)) {
            'GET' => 'viewed',
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted',
            default => 'performed',
        };
    }
}

