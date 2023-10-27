<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the user has any of the specified roles
            foreach ($roles as $role) {
                if (Auth::user()->hasRole($role)) {
                    return $next($request);
                }
            }
        }

        // User doesn't have the required role, so redirect or abort as needed
        return redirect('/')->with('error', 'Unauthorized action.');
    }
}
