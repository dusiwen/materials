<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AlarmTemplateDeviceRequest;
use App\Model\Account;
use App\Model\Device;
use App\Transformers\DeviceTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jericho\Token;
use Jericho\Validate;

class AlarmTemplateDeviceController extends Controller
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
            $v = Validate::firstErrorByRequest($request, new AlarmTemplateDeviceRequest);
            if ($v !== true) $this->response->errorForbidden($v);

            $organizationId = Account::select('organization_id')
                ->where('open_id',
                    Token::ins()
                        ->parse($request->header('token'))
                        ->payload->open_id
                )
                ->firstOrFail()
                ->organization_id;

            DB::table('devices')->whereIn('open_code', explode(',', $request->get('device_open_code')))->where('organization_id', $organizationId)->update(['alarm_template_id' => $request->get('alarm_template_id')]);
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $alarmTemplateId
     * @return \Illuminate\Http\Response
     */
    public function show($alarmTemplateId)
    {
        try {
            $devices = Device::with(['alarmTemplate'])->where('alarm_template_id', $alarmTemplateId)->paginate();
            return $this->response->paginator($devices,new DeviceTransformer);
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
     * @param  int $alarmTemplateId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $alarmTemplateId)
    {
        try {
            $organizationId = Account::select('organization_id')
                ->where('open_id',
                    Token::ins()
                        ->parse($request->header('token'))
                        ->payload->open_id
                )
                ->firstOrFail()
                ->organization_id;
            DB::table('devices')->where('organization_id', $organizationId)->where('alarm_template_id', $alarmTemplateId)->update(['alarm_template_id' => null]);
            DB::table('devices')->whereIn('open_code', explode(',', $request->get('device_open_code')))->where('organization_id', $organizationId)->update(['alarm_template_id' => $alarmTemplateId]);
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
     * @param  int $alarmTemplateId
     * @return \Illuminate\Http\Response
     */
    public function destroy($alarmTemplateId)
    {
        $organizationId = Account::select('organization_id')
            ->where('open_id',
                Token::ins()
                    ->parse(request()->header('token'))
                    ->payload->open_id
            )
            ->firstOrFail()
            ->organization_id;
        DB::table('devices')->where('organization_id', $organizationId)->where('alarm_template_id', $alarmTemplateId)->update(['alarm_template_id' => null]);
        return $this->response->accepted();
    }
}
