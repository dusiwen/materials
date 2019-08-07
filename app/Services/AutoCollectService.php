<?php

namespace App\Services;

use App\Facades\Code;
use App\Model\EntireInstance;
use App\Model\PartInstance;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AutoCollectService
{
    /**
     * 生成自动测试数据
     * @param string $type
     * @param string $factoryDeviceCode
     * @return array|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function makeTestData(string $type, string $factoryDeviceCode)
    {
        switch (strtoupper($type)) {
            case 'ENTIRE':
                return $this->makeEntireTestData($factoryDeviceCode);
                break;
            case 'PART':
                return $this->makePartTestData($factoryDeviceCode);
                break;
        }
    }

    /**
     * 自动生成整件测试数据
     * @param string $entireInstanceFactoryDeviceCode
     * @return array|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function makeEntireTestData(string $entireInstanceFactoryDeviceCode)
    {
        try {
            $currentDate = date('Y-m-d');
            # 通过整件厂编号获取整件对象和模板对象
            $entireInstance = EntireInstance::with([
                'EntireModel',
                'EntireModel.Measurements' => function ($measurement) {
                    $measurement->where('part_model_unique_code', null);
                }
            ])
                ->where('factory_device_code', $entireInstanceFactoryDeviceCode)
                ->firstOrFail();

            # 循环测试标准值生成对应的测试数据
            $entireRecords = [];
            $iEntireRecords = 0;
            foreach ($entireInstance->EntireModel->Measurements as $measurement) {
                ++$iEntireRecords;
                $entireRecords[] = [
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                    'fix_workflow_process_serial_number' => null,
                    'entire_instance_identity_code' => $entireInstance->identity_code,
                    'part_instance_identity_code' => null,
                    'note' => '自动生成的整件测试记录',
                    'measurement_identity_code' => $measurement->identity_code,
                    'measured_value' => '正常',
                    'processor_id' => null,
                    'processed_at' => null,
                    'serial_number' => Code::makeSerialNumber('FIX_WORKFLOW_PROCESS_ENTIRE') . "_{$iEntireRecords}",
                    'type' => 'ENTIRE',
                    'is_allow' => 1
                ];
            }

            return $entireRecords;
        } catch (ModelNotFoundException $exception) {
            throw new \Exception('没有找到对应的整件');
        } catch (\Exception $exception) {
            throw new \Exception('意外错误');
        }
    }

    /**
     * 自动生成部件测试数据
     * @param string $partInstanceFactoryDeviceCode
     * @return array|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function makePartTestData(string $partInstanceFactoryDeviceCode)
    {
        try {
            $currentDate = date('Y-m-d');
            # 通过整件厂编号获取整件对象和模板对象
            $partInstanceIdentityCode = DB::table('part_instances')->where('factory_device_code', $partInstanceFactoryDeviceCode)->first(['identity_code']);
            if (!$partInstanceIdentityCode) throw new \Exception('没有找到对应的部件');
            $partInstanceIdentityCode = $partInstanceIdentityCode->identity_code;

            $partInstance = PartInstance::with([
                'PartModel',
                'PartModel.Measurements',
            ])
                ->where('factory_device_code', $partInstanceFactoryDeviceCode)
                ->firstOrFail();

            # 循环测试标准值生成对应的测试数据
            $partRecords = [];
            $iPart = 0;
            foreach ($partInstance->PartModel->Measurements as $measurement) {
                ++$iPart;
                $partRecords[] = [
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                    'fix_workflow_process_serial_number' => null,
                    'entire_instance_identity_code' => null,
                    'part_instance_identity_code' => $partInstance->identity_code,
                    'note' => '自动采集的部件测试记录',
                    'measurement_identity_code' => $measurement->identity_code,
                    'measured_value' => rand(floatval($measurement->allow_min), floatval($measurement->allow_max)),
                    'processor_id' => null,
                    'processed_at' => null,
                    'serial_number' => Code::makeSerialNumber('FIX_WORKFLOW_PROCESS_PART') . "_{$iPart}",
                    'type' => 'PART',
                    'is_allow' => 1
                ];
            }

            return $partRecords;
        } catch (ModelNotFoundException $exception) {
            throw new \Exception('没有找到对应的整件');
        } catch (\Exception $exception) {
            throw new \Exception('意外错误');
        }
    }

    /**
     * 保存测试值
     * @param array $testData
     */
    public function saveTestData(array $testData)
    {
        DB::table('fix_workflow_records')->insert($testData);
    }
}
