<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class WebCheckLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('account.id')) return $request->ajax() ? Response::make('未登陆', 401) : redirect('login');
        return $next($request);
    }
}
