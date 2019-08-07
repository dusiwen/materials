<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PivotRoleMenuRequest;
use App\Model\PivotRoleMenu;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Jericho\Validate;

class PivotRoleMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $pivots = PivotRoleMenu::with(['role', 'menu'])->orderByDesc('id')->paginate();
        return Response::json($pivots);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request, new PivotRoleMenuRequest);
            if ($v !== true) return Response::make($v, 422);
            foreach (explode(',', $request->get('rbac_menu_id')) as $item) {
                $data[] = ['rbac_role_id' => $request->get('rbac_role_id'), 'rbac_menu_id' => $item];
            }
            DB::table('pivot_role_menus')->insert($data);

            return Response::make();
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $roleId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($roleId)
    {
        try {
            $pivots = PivotRoleMenu::with(['role', 'menu'])->where('rbac_role_id', $roleId)->paginate();
            return Response::json($pivots);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $roleId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $roleId)
    {
        try {
            PivotRoleMenu::where('rbac_role_id', $roleId)->delete();

            foreach (explode(',', $request->get('rbac_menu_id')) as $item) {
                $data[] = ['rbac_role_id' => $roleId, 'rbac_menu_id' => $item];
            }
            DB::table('pivot_role_menus')->insert($data);

            return Response::make();
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $roleId
     * @return \Illuminate\Http\Response
     */
    public function destroy($roleId)
    {
        try {
            PivotRoleMenu::where('rbac_role_id', $roleId)->delete();

            return Response::make();
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }
}
