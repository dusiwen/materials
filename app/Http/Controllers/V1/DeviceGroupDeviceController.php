<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DeviceGroupDeviceRequest;
use App\Model\DeviceGroup;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jericho\Validate;

class DeviceGroupDeviceController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            $v = Validate::firstErrorByRequest($request, new DeviceGroupDeviceRequest);
            if ($v !== true) $this->response->errorForbidden($v);
            $deviceGroup = DeviceGroup::findOrFail($request->get('device_group_id'));
            DB::table('devices')->whereIn('open_code', explode(',', $request->get('device_open_code')))->update(['device_group_id' => $request->get('device_group_id'), 'alarm_template_id' => $deviceGroup->alarm_template_id]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $deviceGroupId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $deviceGroupId)
    {
        try {
            DB::table('devices')->where('device_group_id', $deviceGroupId)->update(['alarm_template_id' => null, 'device_group_id' => null]);
            $deviceGroup = DeviceGroup::findOrFail($deviceGroupId);
            DB::table('devices')->whereIn('open_code', explode(',', $request->get('device_open_code')))->update(['device_group_id' => $deviceGroupId, 'alarm_template_id' => $deviceGroup->alarm_template_id]);
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
     * @param  int $deviceGroupId
     * @return \Illuminate\Http\Response
     */
    public function destroy($deviceGroupId)
    {
        try {
            DB::table('devices')->where('device_group_id', $deviceGroupId)->update(['alarm_template_id' => null, 'device_group_id' => null]);
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
