<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PivotNoticeGroupAccountRequest;
use App\Model\PivotNoticeGroupAccount;
use App\Transformers\PivotNoticeGroupAccountTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jericho\Validate;

class PivotNoticeGroupAccountController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noticeGroupAccounts = PivotNoticeGroupAccount::with(['account','noticeGroup'])->where('id', '>', 0)->orderByDesc('id')->paginate();
        return $this->response->paginator($noticeGroupAccounts, new PivotNoticeGroupAccountTransformer);
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
            $v = Validate::firstErrorByRequest($request,new PivotNoticeGroupAccountRequest);
            if($v!==true) $this->response->errorForbidden($v);
            foreach (explode(',',$request->get('account_id')) as $item) {
                $data[] = ['notice_group_id'=>$request->get('notice_group_id'),'account_id'=>$item];
            }
            DB::table('pivot_notice_group_accounts')->insert($data);

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
     * @param  int $noticeGroupId
     * @return \Illuminate\Http\Response
     */
    public function show($noticeGroupId)
    {
        try {
            $pivots = PivotNoticeGroupAccount::with(['account','noticeGroup'])->where('notice_group_id',$noticeGroupId)->paginate();
            return $this->response->paginator($pivots,new PivotNoticeGroupAccountTransformer);
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $noticeGroupId
     * @return \Illuminate\Http\Response
     */
    public function edit($noticeGroupId)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $noticeGroupId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $noticeGroupId)
    {
        try {
            PivotNoticeGroupAccount::where('notice_group_id',$noticeGroupId)->delete();

            foreach (explode(',',$request->get('account_id')) as $item) {
                $data[] = ['notice_group_id'=>$noticeGroupId,'account_id'=>$item];
            }
            DB::table('pivot_notice_group_accounts')->insert($data);

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
     * @param  int $noticeGroupId
     * @return \Illuminate\Http\Response
     */
    public function destroy($noticeGroupId)
    {
        try {
            PivotNoticeGroupAccount::where('notice_group_id',$noticeGroupId)->delete();

            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
