<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PivotNoticeGroupDeviceRequest;
use App\Model\PivotNoticeGroupDevice;
use App\Transformers\PivotNoticeGroupDeviceTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jericho\Validate;

class PivotNoticeGroupDeviceController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noticeGroupAccounts = PivotNoticeGroupDevice::with(['device', 'noticeGroup'])->where('id', '>', 0)->orderByDesc('id')->paginate();
        return $this->response->paginator($noticeGroupAccounts, new PivotNoticeGroupDeviceTransformer);
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
            $v = Validate::firstErrorByRequest($request, new PivotNoticeGroupDeviceRequest);
            if ($v !== true) $this->response->errorForbidden($v);
            foreach (explode(',', $request->get('device_open_code')) as $item) {
                $data[] = [
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'notice_group_id' => $request->get('notice_group_id'),
                    'device_open_code' => $item
                ];
            }
            DB::table('pivot_notice_group_devices')->insert($data);

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
            $pivots = PivotNoticeGroupDevice::with(['device', 'noticeGroup'])->where('notice_group_id', $noticeGroupId)->paginate();
            return $this->response->paginator($pivots, new PivotNoticeGroupDeviceTransformer);
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
        //
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
            PivotNoticeGroupDevice::where('notice_group_id', $noticeGroupId)->delete();

            foreach (explode(',', $request->get('device_open_code')) as $item) {
                $data[] = [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'notice_group_id' => $noticeGroupId,
                    'device_open_code' => $item
                ];
            }
            DB::table('pivot_notice_group_devices')->insert($data);

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
            PivotNoticeGroupDevice::where('notice_group_id', $noticeGroupId)->delete();

            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
