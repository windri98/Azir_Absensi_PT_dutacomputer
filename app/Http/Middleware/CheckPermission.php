<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  The required permission
     * @param  string  $guard  The guard to check against
     */
    public function handle(Request $request, Closure $next, string $permission, string $guard = 'web'): Response
    {
        // Check if user is authenticated
        if (!auth($guard)->check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = auth($guard)->user();

        // Admin role bypasses all permission checks
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. You do not have permission to access this resource.',
                    'required_permission' => $permission
                ], 403);
            }

            abort(403, 'Unauthorized action. Required permission: ' . $permission);
        }

        return $next($request);
    }
}
