<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            // Log the user details for debugging
            Log::debug('Admin Middleware: Non-admin or unauthenticated user attempted to access admin route', [
                'user' => auth()->user(),
                'route' => $request->path(),
            ]);

            // Redirect non-admin users
            return redirect('user.home')->with('error', 'You do not have admin access');
        }

        // Log if the user is authenticated and is an admin
        Log::debug('Admin Middleware: Authenticated Admin', [
            'user' => auth()->user(),
        ]);

        // Allow the request to proceed for admins
        return $next($request);
    }
}
