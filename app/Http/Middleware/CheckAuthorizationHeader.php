<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuthorizationHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the Authorization header is present
        if ($request->header('Authorization')) {
            // If the header is present, you can perform additional checks or actions here
            // For example, you can log it or perform some validation

            // If the conditions are met, continue to the next middleware or route
            return $next($request);
        }

        // If the Authorization header is not present, you can respond accordingly
        return $next($request);
    }
}
