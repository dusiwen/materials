<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DeviceRequest;
use App\Model\Device;
use App\Model\DeviceSku;
use App\Model\DeviceSpu;
use App\Model\Organization;
use App\Transformers\DeviceTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jericho\Redis\Hashs;
use Jericho\Validate;

class DeviceController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = Device::with(['organization', 'spu', 'sku','alarmTemplate'])->where('id', '>', 0)->orderByDesc('id')->paginate();
        return $this->response->paginator($devices, new DeviceTransformer);
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
            $v = Validate::firstErrorByRequest($request, new DeviceRequest);
            if ($v !== true) $this->response->errorForbidden($v);

            $uploadFile = $request->file('file')->getRealPath();
            $time = date('Y-m-d H:i:s');

            # 获取SPU数据
            $spu = DeviceSpu::findOrFail($request->get('spu_id'));
            $sku = DeviceSku::findOrFail($request->get('sku_id'));
            $organization = Organization::findOrFail($request->get('organization_id'));
            $level = $organization->level;

            if ($request->file('file')) {
                $fileType = \PHPExcel_IOFactory::identify($uploadFile);
                $reader = \PHPExcel_IOFactory::createReader($fileType);
                $excel = $reader->load($uploadFile);
                $sheet = $excel->getSheet(0);
                $rows = $sheet->getHighestRow();
                $columns = $sheet->getHighestColumn();
                for ($i = 2; $i <= $rows; $i++) {
                    $rowData = $sheet->rangeToArray('A' . $i . ':' . $columns . $i, NULL, TRUE, FALSE)[0];
                    $deviceData[] = [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'name' => $spu->name . ':' . $sku->name . '_' . Hashs::ins()->setIncr('count', 'device:' . $request->get('sku_id')),
                        'spu_id' => $request->get('spu_id'),
                        'sku_id' => $request->get('sku_id'),
                        'organization_id' => $request->get('organization_id'),
                        'level' => $level,
                        'open_code' => $rowData[0],
                        'sort' => intval($rowData[1]),
                        'alarm_type' => $rowData[2],
                    ];
                }
                DB::table('devices')->insert($deviceData);
                return $this->response->created();
            }
            $this->response->errorInternal('缺少上传文件');
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
            $device = Device::with(['organization', 'spu', 'sku','alarmTemplate'])->where('id', $id)->firstOrFail();
            return $this->response->item($device, new DeviceTransformer);
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
            $device = Device::findOrFail($id);
            $device->fill($request->all());
            $device->saveOrFail();
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
            $device = Device::findOrFail($id);
            $device->delete();
            if (!$device->trashed()) $this->response->errorInternal('删除失败');
            return $this->response->accepted();
        } catch (ModelNotFoundException $exception) {
            $this->response->errorNotFound($exception->getMessage());
        } catch (\Exception $exception) {
            $this->response->errorInternal($exception->getMessage());
        }
    }
}
