<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RbacMenuRequest;
use App\Model\PivotRoleMenu;
use App\Model\RbacMenu;
use App\Model\RbacPermission;
use App\Model\RbacRole;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Jericho\Validate;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = RbacMenu::with(['parent'])->orderByDesc('id')->paginate();
        return Response::view('Rbac.Menu.index', ['menus' => $menus]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = RbacMenu::orderByDesc('id')->where('parent_id', 0)->get();
        $permissions = RbacPermission::all();
        $roles = RbacRole::all();
        return Response::view('Rbac.Menu.create', [
            'menus' => $menus,
            'permissions' => $permissions,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new RbacMenuRequest);
            if ($v !== true) return Response::make($v, 422);

            $menu = new RbacMenu;
            $menu->fill($request->all());
            $menu->saveOrFail();


            # 绑定新关系
            if ($request->has('role_ids')) {
                $insertData = [];
                foreach (request()->get('role_ids') as $item) {
                    $insertData[] = ['rbac_menu_id' => $menu->id, 'rbac_role_id' => $item];
                }
                $insertResult = DB::table('pivot_role_menus')->insert($insertData);
                if (!$insertResult) return Response::make('绑定失败', 500);
            }

            return Response::make('创建成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $parentMenus = RbacMenu::orderByDesc('id')
                ->where(function ($my) {
                    $my->where('parent_id', 0)
                        ->orWhere('parent_id', null);
                })
                ->where('id', '<>', $id)
                ->get();
            $roles = RbacRole::all();
            $menu = RbacMenu::with(['roles'])->where('id', $id)->firstOrFail();
            $roleIds = [];
            foreach ($menu->roles as $role) {
                $roleIds[] = $role->id;
            }
            return Response::view('Rbac.Menu.edit', [
                'menu' => $menu,
                'parent_menus' => $parentMenus,
                'roles' => $roles,
                'roleIds' => $roleIds
            ]);
        } catch (ModelNotFoundException $exception) {
            return back()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            return back()->with('danger', '意外错误');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new RbacMenuRequest);
            if ($v !== true) return Response::make($v, 422);

            $menu = RbacMenu::findOrFail($id);
            $menu->fill($request->all());
            $menu->saveOrFail();

            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $menu = RbacMenu::findOrFail($id);
            $menu->delete();
            if (!$menu->trashed()) return Response::make('删除失败', 403);
            return Response::make('删除成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 绑定菜单到角色
     * @param int $menuId 菜单编号
     * @return \Illuminate\Http\Response
     */
    public function bindRoles($menuId)
    {
        try {
            PivotRoleMenu::where('rbac_menu_id', $menuId)->delete();  # 删除原绑定信息

            # 删除原绑定关系
            DB::table('pivot_role_menus')->where('rbac_menu_id', $menuId)->delete();
            if (\request()->has('role_ids')) {
                # 绑定新关系
                $insertData = [];
                foreach (request()->get('role_ids') as $item) {
                    $insertData[] = ['rbac_menu_id' => $menuId, 'rbac_role_id' => $item];
                }
                $insertResult = DB::table('pivot_role_menus')->insert($insertData);
                if (!$insertResult) return Response::make('绑定失败', 500);
            }


            return Response::make('绑定成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误:' . $exception->getMessage(), 500);
        }
    }
}
