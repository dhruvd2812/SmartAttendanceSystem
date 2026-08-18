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
        | Get Logged-in User
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Check User Role
        |--------------------------------------------------------------------------
        */

        if (!in_array($user->role, $roles, true)) {
            abort(403, 'You are not authorized to access this page.');
        }


        /*
        |--------------------------------------------------------------------------
        | Allow Request
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}