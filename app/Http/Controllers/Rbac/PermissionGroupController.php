<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RbacPermissionGroupRequest;
use App\Model\RbacPermissionGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Jericho\Validate;

class PermissionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = RbacPermissionGroup::orderByDesc('id')->paginate();
        return Response::view('Rbac.PermissionGroup.index', ['groups' => $groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Response::view('Rbac.PermissionGroup.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new RbacPermissionGroupRequest);
            if ($v !== true) return Response::make($v, 404);
            $group = new RbacPermissionGroup;
            $group->name = $request->get('name');
            $group->saveOrFail();

            # 如果是资源路由则一次性创建路由
            if ($request->get('is_resource') == 1) {
                $name = $request->get('name');
                $time = date('Y-m-d H:i:s');
                $actionName = $request->get('action_name');
                $insertData = [
                    # 列表
                    [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'name' => "{$name}列表",
                        'http_path' => "{$actionName}.index",
                        'rbac_permission_group_id' => $group->id
                    ],
                    # 新建页面
                    [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'name' => "新建{$name}页面",
                        'http_path' => "{$actionName}.create",
                        'rbac_permission_group_id' => $group->id
                    ],
                    # 新建
                    [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'name' => "新建{$name}",
                        'http_path' => "{$actionName}.store",
                        'rbac_permission_group_id' => $group->id
                    ],
                    # 详情页面
                    [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'name' => "详情{$name}页面",
                        'http_path' => "{$actionName}.show",
                        'rbac_permission_group_id' => $group->id
                    ],
                    # 编辑页面
                    [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'name' => "编辑{$name}页面",
                        'http_path' => "{$actionName}.edit",
                        'rbac_permission_group_id' => $group->id
                    ],
                    # 编辑
                    [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'name' => "编辑{$name}",
                        'http_path' => "{$actionName}.update",
                        'rbac_permission_group_id' => $group->id
                    ],
                    # 删除
                    [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'name' => "删除{$name}",
                        'http_path' => "{$actionName}.delete",
                        'rbac_permission_group_id' => $group->id
                    ],
                ];
                DB::table('rbac_permissions')->insert($insertData);
            }

            return Response::make('新建成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $group = RbacPermissionGroup::findOrFail($id);
            return Response::view('Rbac.PermissionGroup.edit', ['rbacPermissionGroup' => $group]);

        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('danger', '意外错误');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new RbacPermissionGroupRequest);
            if ($v !== true) return Response::make($v, 422);

            $group = RbacPermissionGroup::findOrFail($id);
            $group->fill($request->all());
            $group->saveOrFail();

            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('资源不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $group = RbacPermissionGroup::findOrFail($id);
            $group->delete();
            if (!$group->trashed()) return Response::make('删除失败', 500);

            return Response::make('删除成功');
        } catch (ModelNotFoundException $exception) {
            return back()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->with('danger', '意外错误');
        }
    }
}
