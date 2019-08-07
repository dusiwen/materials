<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PivotRolePermissionRequest;
use App\Model\PivotRolePermission;
use App\Transformers\PivotRolePermissionTransformer;
use App\Transformers\RbacPermissionTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jericho\Validate;

class PivotRolePermissionController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pivot = PivotRolePermission::with(['role', 'permission'])->where('id', '>', 0)->orderByDesc('id')->paginate();
        return $this->response->paginator($pivot, new PivotRolePermissionTransformer);
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
            $v = Validate::firstErrorByRequest($request, new PivotRolePermissionRequest);
            if ($v !== true) $this->response->errorForbidden($v);
            foreach (explode(',', $request->get('rbac_permission_id')) as $item) {
                $data[] = ['rbac_role_id' => $request->get('rbac_role_id'), 'rbac_permission_id' => $item];
            }
            DB::table('pivot_role_permissions')->insert($data);


            return $this->response->created();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $roleId
     * @return \Illuminate\Http\Response
     */
    public function show($roleId)
    {
        try {
            $pivots = PivotRolePermission::with(['permission'])->where('rbac_role_id', $roleId)->paginate();
            return $this->response->paginator($pivots, new RbacPermissionTransformer);
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
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
            PivotRolePermission::where('rbac_role_id', $roleId)->delete();

            foreach (explode(',', $request->get('rbac_permission_id')) as $item) {
                $data[] = ['rbac_role_id' => $roleId, 'rbac_permission_id' => $item];
            }
            DB::table('pivot_role_permissions')->insert($data);

            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
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
            PivotRolePermission::where('rbac_role_id', $roleId)->delete();

            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
