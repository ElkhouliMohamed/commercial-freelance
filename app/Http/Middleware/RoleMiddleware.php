<?php
/*
 * This file is part of the Laravel framework.
 *
 * (c) Laravel <https://laravel.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            abort(403, 'User is not authenticated.');
        }

        // Check if the user has any of the specified roles
        if (!Auth::user()->hasAnyRole($roles)) {
            // Debugging: Output the user's roles (remove this in production)
            dd(Auth::user()->getRoleNames());

            // Abort with a 403 error if the user does not have the required roles
            abort(403, 'User does not have the right roles.');
        }

        // Allow the request to proceed
        return $next($request);
    }
}