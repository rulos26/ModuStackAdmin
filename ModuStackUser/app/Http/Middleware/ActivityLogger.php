<?php

namespace App\Http\Middleware;

use App\Models\UserActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar si el usuario está autenticado
        if (auth()->check() && $request->method() !== 'GET') {
            $this->logActivity($request);
        }

        return $response;
    }

    /**
     * Log the activity.
     */
    protected function logActivity(Request $request): void
    {
        $user = auth()->user();

        UserActivityLog::create([
            'user_id' => $user->id,
            'action' => $this->getAction($request),
            'model_type' => $this->getModelType($request),
            'model_id' => $this->getModelId($request),
            'description' => $this->getDescription($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);
    }

    /**
     * Get the action name from the request.
     */
    protected function getAction(Request $request): string
    {
        $route = $request->route();
        if ($route) {
            $action = $route->getActionMethod();
            return match ($action) {
                'store' => 'create',
                'update' => 'update',
                'destroy' => 'delete',
                default => $action,
            };
        }
        return strtolower($request->method());
    }

    /**
     * Get the model type from the request.
     */
    protected function getModelType(Request $request): ?string
    {
        $route = $request->route();
        if ($route) {
            $parameters = $route->parameters();
            foreach ($parameters as $key => $value) {
                if (is_object($value) && method_exists($value, 'getMorphClass')) {
                    return get_class($value);
                }
            }
        }
        return null;
    }

    /**
     * Get the model ID from the request.
     */
    protected function getModelId(Request $request): ?int
    {
        $route = $request->route();
        if ($route) {
            $parameters = $route->parameters();
            foreach ($parameters as $key => $value) {
                if (is_object($value) && method_exists($value, 'getKey')) {
                    return $value->getKey();
                }
                if (is_numeric($value)) {
                    return (int) $value;
                }
            }
        }
        return null;
    }

    /**
     * Get the description from the request.
     */
    protected function getDescription(Request $request): ?string
    {
        $action = $this->getAction($request);
        $route = $request->route();
        
        if ($route) {
            $routeName = $route->getName();
            return "{$action}: {$routeName}";
        }
        
        return "{$action}: {$request->path()}";
    }
}
