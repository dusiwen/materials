<?php

namespace App\Http\Middleware;

use Closure;
use Jericho\Redis\Strings;
use Jericho\Token;

class WebCheckJwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = Token::ins()->parse($request->header('token'));
            $jwts = json_decode(Strings::ins()->getOne('account:' . $token->payload->account), true);
            if (!in_array($request->header('token'),$jwts)) return response('令牌失效',500);
        } catch (\Exception $exception) {
            return response('jwt-token:'.$exception->getMessage(),500);
        }

        return $next($request);
    }
}
