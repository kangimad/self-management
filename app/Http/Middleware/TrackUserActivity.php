<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Update user activity if authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Only update activity every 2 minutes to avoid too many DB updates
            if (!$user->last_activity_at || $user->last_activity_at->diffInMinutes(now()) >= 2) {
                $user->updateActivity();
            }
        }

        return $next($request);
    }
}
