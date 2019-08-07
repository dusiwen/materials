<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\PivotRoleAccountRequest;
use App\Model\PivotRoleAccount;
use App\Transformers\PivotRoleAccountTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jericho\Validate;

class PivotRoleAccountController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pivot = PivotRoleAccount::with(['role','account'])->where('id','>',0)->orderByDesc('id')->paginate();
        return $this->response->paginator($pivot,new PivotRoleAccountTransformer);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $v = Validate::firstErrorByRequest($request,new PivotRoleAccountRequest);
            if($v!==true) $this->response->errorForbidden($v);
            foreach (explode(',',$request->get('account_id')) as $item) {
                $data[] = ['rbac_role_id'=>$request->get('rbac_role_id'),'account_id'=>$item];
            }
            DB::table('pivot_role_accounts')->insert($data);


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
     * @param  int  $roleId
     * @return \Illuminate\Http\Response
     */
    public function show($roleId)
    {
        try {
            $pivots = PivotRoleAccount::with(['account'])->where('rbac_role_id',$roleId)->paginate();
            return $this->response->paginator($pivots,new PivotRoleAccountTransformer);
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $roleId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $roleId)
    {
        try {
            PivotRoleAccount::where('rbac_role_id',$roleId)->delete();

            foreach (explode(',',$request->get('account_id')) as $item) {
                $data[] = ['rbac_role_id'=>$roleId,'account_id'=>$item];
            }
            DB::table('pivot_role_accounts')->insert($data);

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
     * @param  int  $roleId
     * @return \Illuminate\Http\Response
     */
    public function destroy($roleId)
    {
        try {
            PivotRoleAccount::where('rbac_role_id',$roleId)->delete();

            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
