<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrPermission
{
    /**
     * Handle an incoming request.
     * Check if user has required role OR permission
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $roleOrPermission  Role name or permission name required
     * @param  string  $guard  The guard to check against
     */
    public function handle(Request $request, Closure $next, string $roleOrPermission, string $guard = 'web'): Response
    {
        // Check if user is authenticated
        if (!auth($guard)->check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = auth($guard)->user();

        // Check if it's a role (roles typically don't have dots)
        $isRole = !str_contains($roleOrPermission, '.');
        
        $hasAccess = false;
        
        if ($isRole) {
            // Check role
            $hasAccess = $user->hasRole($roleOrPermission);
        } else {
            // Check permission
            $hasAccess = $user->hasPermission($roleOrPermission);
        }
        
        // Admin and superadmin always have access
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. You do not have required access.',
                    'required' => $roleOrPermission,
                    'type' => $isRole ? 'role' : 'permission'
                ], 403);
            }

            abort(403, "Unauthorized action. Required {$roleOrPermission}");
        }

        return $next($request);
    }
}
