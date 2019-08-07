<?php

namespace App\Services;

use App\Facades\Alarm;
use App\Model\Device;
use Illuminate\Support\Facades\DB;

class ReportSensorService
{
    private $_datetime = null;

    public function processData($request)
    {
        $this->_datetime = date('Y-m-d H:i:s');
        # 获取设备对象
        $device = Device::with(['sku', 'sku.template','deviceGroup'])->where('open_code', $request['device_open_code'])->firstOrFail();

        # 插入数据
        $alarmReports = $this->insertData($device, $request);
        if ($alarmReports) {
            return Alarm::process($device, $alarmReports, $this->_datetime,$request);
        }
    }

    /**
     * 将数据写入数据
     * @param Device $device 设备对象
     * @param array $reports 报表数据
     * @return array
     */
    public function insertData(Device $device, $request)
    {
        $alarmReports = [];

        $input = [
            'device_open_code' => $device->open_code,
            'source_data' => $request['source_data'],
            'template_id' => $request['template_id'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $keys = null;
        foreach (json_decode($request['data'],true) as $key => $value) {
            $keyName = $value['key_name'];
            $keys[] = $keyName;
            $input["{$keyName}_value"] = $value['value'];
            $input["{$keyName}_unit"] = $value['unit'];
            $input["{$keyName}_cn_name"] = $value['cn_name'];
            $input["{$keyName}_condition_origin"] = $value['condition']['origin'];
            $input["{$keyName}_condition_finish"] = $value['condition']['finish'];
            $input["{$keyName}_condition_level"] = $value['condition']['level'];
            $input["{$keyName}_condition_description"] = $value['condition']['description'];

            # 如果超过阀值则加入需要报警行列
            if ($value['condition']['level'] > 0) $alarmReports[] = $key;
        }
        $input['keys'] = implode(',',$keys);
        DB::table('report_sensor_' . $device->sku_id)->insert($input);
        return $alarmReports;
    }
}
