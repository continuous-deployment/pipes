<?php

namespace App\Http\Middleware;

use Closure;

class ExampleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request incomming request
     * @param  \Closure                 $next    next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
