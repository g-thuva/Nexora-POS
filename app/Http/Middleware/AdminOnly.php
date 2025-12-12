<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthenticated', 'message' => 'Please log in to access this resource.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Forbidden', 'message' => 'Access denied. Admin privileges required.'], 403);
            }
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}