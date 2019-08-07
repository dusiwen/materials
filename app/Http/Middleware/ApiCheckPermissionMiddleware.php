<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiCheckPermissionMiddleware
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
        $currentUri = $request->route()->getName();
        $currentMethod = $request->getMethod();
//        dump($currentMethod . '.' . $currentUri);
        try {
//            $currentPermissionId = RbacPermission::select('id')->where('http_path', $currentUri)->where(function ($query) use ($currentMethod) {
//                $query->where('http_method', 'ALL')->orWhere('http_method', $currentMethod);
//            })->firstOrFail()->id;
//
//            if(!Rbac::check($currentPermissionId)) $this->response->errorMethodNotAllowed('无权访问');
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
        return $next($request);
    }
}
