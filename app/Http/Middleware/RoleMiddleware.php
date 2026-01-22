<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware(['auth','role:admin']) or ->middleware(['auth','role:admin,petugas'])
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        if (! Auth::check()) {
            return redirect()->route('login.create');
        }

        $user = Auth::user();

        // Split roles by comma and trim whitespace
        $allowedRoles = array_map('trim', explode(',', $roles));

        // Check if user's role is in allowed roles
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Unauthorized - Insufficient permissions');
        }

        return $next($request);
    }
}
