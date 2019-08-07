<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Helpers;
use Jericho\Redis\Strings;
use Jericho\Token;

class ApiCheckJwtMiddleware
{
    use Helpers;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = Token::ins()->parse($request->header('token'));
            $jwts = json_decode(Strings::ins()->getOne('account:' . $token->payload->account), true);
            if (!in_array($request->header('token'), $jwts)) $this->response->errorForbidden('令牌失效');
        } catch (\Exception $exception) {
            $this->response->errorInternal('jwt-token:' . $exception->getMessage());
        }

        return $next($request);
    }
}
