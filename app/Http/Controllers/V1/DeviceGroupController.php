<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DeviceGroupRequest;
use App\Model\DeviceGroup;
use App\Transformers\DeviceGroupTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jericho\Validate;

class DeviceGroupController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deviceGroups = DeviceGroup::with(['organization','alarmTemplate'])->orderBy('id')->paginate();
        return $this->response->paginator($deviceGroups, new DeviceGroupTransformer);
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
            $v = Validate::firstErrorByRequest($request,new DeviceGroupRequest);
            if($v!==true) $this->response->errorForbidden($v);
            $deviceGroup = new DeviceGroup;
            $deviceGroup->fill($request->all());
            $deviceGroup->saveOrFail();

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
        try {
            $deviceGroup = DeviceGroup::with(['organization','alarmTemplate','devices'])->findOrFail($id);
            return $this->response->item($deviceGroup,new DeviceGroupTransformer);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $v = Validate::firstErrorByRequest($request,new DeviceGroupRequest);
            if($v!==true) $this->response->errorForbidden($v);

            $deviceGroup = DeviceGroup::findOrFail($id);
            $deviceGroup->fill($request->all());
            $deviceGroup->saveOrFail();

            DB::table('devices')->where('device_group_id',$deviceGroup->id)->update(['alarm_template_id'=>$deviceGroup->alarm_template_id]);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deviceGroup = DeviceGroup::findOrFail($id);
            $deviceGroup->delete();
            if (!$deviceGroup->trashed()) $this->response->errorInternal('删除失败');
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    public function bindDevice()
    {

    }
}
