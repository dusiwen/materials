<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jericho\HttpResponse;

class GetCurrentMenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            session()->put('account.currentMenu', explode('.',$request->route()->getAction('as'))[0]);
        } catch (ModelNotFoundException $exception) {
            session()->put('account.currentMenu', 0);
        } catch (\Exception $exception) {
            return HttpResponse::make('意外错误', 500);
        }

        return $next($request);
    }
}
