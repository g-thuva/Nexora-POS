<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // If no roles specified, just check if user is authenticated
        if (empty($roles)) {
            return $next($request);
        }

        // Super admin has access to everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has any of the required roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Check for special role permissions
        foreach ($roles as $role) {
            switch ($role) {
                case 'admin':
                    if ($user->isAdmin()) {
                        return $next($request);
                    }
                    break;
                case 'shop_owner':
                    if ($user->hasFullAccess() || $user->isShopOwner()) {
                        return $next($request);
                    }
                    break;
                case 'manager':
                    if ($user->hasInventoryAccess()) {
                        return $next($request);
                    }
                    break;
                case 'inventory_access':
                    if ($user->hasInventoryAccess()) {
                        return $next($request);
                    }
                    break;
                case 'reports_access':
                    if ($user->canAccessReports()) {
                        return $next($request);
                    }
                    break;
                case 'user_management':
                    if ($user->canManageUsers()) {
                        return $next($request);
                    }
                    break;
            }
        }

        // If user doesn't have required permissions, redirect with error
        return redirect()->back()->with('error', 'You do not have permission to access this area.');
    }
}
