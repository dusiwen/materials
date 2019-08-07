<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RbacRoleResquest;
use App\Model\PivotRolePermission;
use App\Model\RbacPermission;
use App\Model\RbacPermissionGroup;
use App\Model\RbacRole;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Jericho\Validate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = RbacRole::with(['accounts'])->where('id', '>', 0)->orderByDesc('id')->paginate();
        return Response::view('Rbac.Role.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Response::view('Rbac.Role.create');
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
            $v = Validate::firstErrorByRequest($request, new RbacRoleResquest);
            if ($v !== true) return Response::make($v, 422);
            $role = new RbacRole;
            $role->fill($request->all());
            $role->saveOrFail();

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {

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
            $role = RbacRole::with('permissions')->findOrFail($id);
            $permissionIds = [];
            foreach ($role->permissions as $permission) {
                $permissionIds[] = $permission->id;
            }

            $permissionGroups = RbacPermissionGroup::with(['permissions'])->get();
            $permissions = RbacPermission::all();

            return Response::view('Rbac.Role.edit', [
                'role' => $role,
                'permissions' => $permissions,
                'permissionIds' => $permissionIds,
                'permissionGroups' => $permissionGroups
            ]);
        } catch (ModelNotFoundException $exception) {
            return back()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return back()->with('danger', '意外错误:');
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
            $v = Validate::firstErrorByRequest($request, new RbacRoleResquest);
            if ($v !== true) return Response::make($v, 422);

            $role = RbacRole::findOrFail($id);
            $role->fill($request->all());
            $role->saveOrFail();

            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
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
            $role = RbacRole::findOrFail($id);
            $role->delete();
            if (!$role->trashed()) return Response::make('删除失败', 403);

            return Response::make('删除成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 绑定角色到权限
     * @param int $roleId 角色编号
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function bindPermissions($roleId)
    {
        try {
            PivotRolePermission::where('rbac_role_id', $roleId)->delete();  # 删除原绑定信息

            # 绑定新关系
            $insertData = [];
            foreach (request()->get('permission_ids') as $item) {
                $insertData[] = ['rbac_role_id' => $roleId, 'rbac_permission_id' => $item];
            }
            $insertResult = DB::table('pivot_role_permissions')->insert($insertData);
            if (!$insertResult) return Response::make('绑定失败', 500);

            return Response::make('绑定成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误:' . $exception->getMessage(), 500);
        }
    }
}
