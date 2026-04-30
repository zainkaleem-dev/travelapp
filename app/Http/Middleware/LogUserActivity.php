<?php

namespace App\Http\Middleware;

use App\Helpers\ActivityLogHelper;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $trackedModel = $this->resolveTrackedModel($request);
        $beforeState = $trackedModel?->attributesToArray();

        $response = $next($request);

        if (!auth()->check()) {
            return $response;
        }

        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $response;
        }

        try {
            ActivityLogHelper::storeFromRequest($request, [
                'status_code' => $response->getStatusCode(),
            ], $beforeState, $this->resolveAfterState($trackedModel));
        } catch (\Throwable) {
            // Logging should never break user flows.
        }

        return $response;
    }

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

    private function resolveAfterState(?Model $trackedModel): ?array
    {
        if (!$trackedModel) {
            return null;
        }

        $fresh = $trackedModel->fresh();

        return $fresh?->attributesToArray();
    }
}

