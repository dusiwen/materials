<?php

namespace App\Http\Middleware;

use App\Model\RbacMenu;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WebCheckPermissionMiddleware
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
            # 获取当前权限编号
            $currentAction = $request->route()->getAction()['as'];
            $currentPrefix = $request->route()->getPrefix();
            if ($currentPrefix) $currentAction = "{$currentPrefix}.{$currentAction}";
//            $currentPermission = RbacPermission::where('http_path', $currentAction)->firstOrFail();
            # 检查是否具备权限
//            if (!in_array($currentPermission->id, session()->get('account.permissionIds'))) return back()->withInput()->with('danger', '无权操作');
        } catch (ModelNotFoundException $exception) {
            return $request->ajax() ? Response::make('找不到对应权限:' . $currentAction, 403) : back()->withInput()->with('danger', '权限不足:' . $currentAction);
        } catch (\Exception $exception) {
            return $request->ajax() ? Response::make('check-permission:' . $exception->getMessage(), 403) : back()->withInput()->with('check-permission:' . $exception->getMessage());
        }
        return $next($request);
    }
}
