<?php

// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('message', 'Please log in first');
        }

        // Check if the authenticated user has the specified role
        $user = Auth::user();
        if (!in_array($user->role, $roles)) {
            return redirect('/')->with('message', 'You do not have permission to access this page');
        }

        return $next($request);
    }
}

