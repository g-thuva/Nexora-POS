<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_suspended) {
            $user = Auth::user();

            // Check if temporary suspension has expired
            if ($user->suspension_ends_at && now()->greaterThan($user->suspension_ends_at)) {
                // Auto-unsuspend user
                $user->update([
                    'is_suspended' => false,
                    'suspension_reason' => null,
                    'suspension_type' => null,
                    'suspension_duration' => null,
                    'suspended_at' => null,
                    'suspension_ends_at' => null,
                    'suspended_by' => null
                ]);

                return $next($request);
            }

            // User is still suspended - show suspended page
            return response()->view('auth.suspended', [
                'suspensionReason' => $user->suspension_reason,
                'suspensionType' => $user->suspension_type,
                'suspendedAt' => $user->suspended_at,
                'suspensionEndsAt' => $user->suspension_ends_at,
            ]);
        }

        return $next($request);
    }
}
