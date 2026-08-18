<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | User must be logged in
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {
            return redirect()->route('login');
        }


        /*
        |--------------------------------------------------------------------------
        | Get logged-in user's role
        |--------------------------------------------------------------------------
        */

        $userRole = auth()->user()->role;


        /*
        |--------------------------------------------------------------------------
        | Check role permission
        |--------------------------------------------------------------------------
        */

        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized action.');
        }


        /*
        |--------------------------------------------------------------------------
        | Continue request
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}