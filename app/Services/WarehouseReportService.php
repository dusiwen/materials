<?php

namespace App\Services;

use App\Facades\Code;
use App\Model\EntireInstance;
use App\Model\EntireInstanceLog;
use App\Model\FixWorkflow;
use App\Model\PartInstance;
use App\Model\WarehouseReport;
use App\Model\WarehouseReportEntireInstance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Jericho\Time;

class WarehouseReportService
{
    /**
     * 采购入所
     * @param Request $request
     * @return string
     */
    public function buyInOnce(Request $request): string
    {
        # 生成部件档案
        $currentTime = date('Y-m-d H:i:s');
        $newEntireInstanceIdentityCode = Code::makeEntireInstanceIdentityCode($request->get('entire_model_unique_code'));

        # 获取部件数据
        $parts = $request->except([
            'type', '_token',
            'category_unique_code',
            'entire_model_unique_code',
            'factory_name',
            'factory_device_code',
            'processor_id',
            'connection_name',
            'connection_phone',
            'processed_at',
            'auto_insert_fix_workflow',
            'category_unique_name',
        ]);

        DB::transaction(function () use ($request, $parts, $newEntireInstanceIdentityCode, $currentTime) {
            # 检车出厂设备编号是否重复
            $entireInstance = EntireInstance::where('factory_device_code', $request->get('factory_device_code'))->first();
            if ($entireInstance) throw new Exception('该设备已经被加入：' . $request->get('factory_device_code'));

            # 插入整件实例
            $entireInstance = new EntireInstance;
            $entireInstance->fill([
                'entire_model_unique_code' => $request->get('entire_model_unique_code'),
                'status' => $request->get('type'),
                'factory_name' => $request->get('factory_name'),
                'factory_device_code' => $request->get('factory_device_code'),
                'identity_code' => $newEntireInstanceIdentityCode,
                'in_warehouse' => false,
                'category_unique_code' => $request->get('category_unique_code'),
                'is_flush_serial_number' => true,
            ])
                ->saveOrFail();

            $partModelCount = DB::table('pivot_entire_model_and_part_models')->where('entire_model_unique_code', $request->get('entire_model_unique_code'))->count('part_model_unique_code');
            if ($partModelCount) {
                $i = 0;
                $partInstances = [];
                $entireInstanceLogs = [];
                foreach ($parts as $partModelUniqueCode => $part) {
                    foreach ($part as $partFactoryDeviceCode) {
                        if ($partFactoryDeviceCode == null) continue;
                        $i += 1;
                        # 检查部件是否重复
                        $partInstance = PartInstance::where('factory_device_code', $partFactoryDeviceCode)->first();
                        if ($partInstance) throw new Exception('部件已经被加入：' . $partFactoryDeviceCode);
                        if ($partInstance) throw new Exception($partInstance->toJson());

                        # 插入部件实例
                        $partInstances[] = [
                            'created_at' => $currentTime,
                            'updated_at' => $currentTime,
                            'part_model_unique_code' => $partModelUniqueCode,
                            'entire_instance_identity_code' => $newEntireInstanceIdentityCode,
                            'status' => $request['type'],
                            'factory_name' => $request['factory_name'],
                            'factory_device_code' => $partFactoryDeviceCode,
                            'identity_code' => Code::makePartInstanceIdentityCode($partModelUniqueCode, $request->get('entire_model_unique_code')) . "_{$i}",
                        ];

                        # 插入整件操作日志
                        $entireInstanceLogs[] = [
                            'created_at' => $currentTime,
                            'updated_at' => $currentTime,
                            'name' => EntireInstance::$STATUS[$request['type']],
                            'description' => "包含部件{$partFactoryDeviceCode}（{$partModelUniqueCode}）",
                            'entire_instance_identity_code' => $newEntireInstanceIdentityCode
                        ];
                    }
                }

                if ($partInstances == []) throw new Exception('部件不能为空');
                if (!DB::table('part_instances')->insert($partInstances)) throw new Exception('创建部件失败');
                if (!DB::table('entire_instance_logs')->insert($entireInstanceLogs)) throw new \Exception('创建整件实例操作日志失败');
            }

            # 插入整件操作日志
            $entireInstanceLog = new EntireInstanceLog;
            $entireInstanceLog->fill([
                'name' => EntireInstance::$STATUS[$request['type']],
                'entire_instance_identity_code' => $newEntireInstanceIdentityCode,
            ])->saveOrFail();

            # 生成入所单
            $warehouseReport = new WarehouseReport;
            $warehouseReport->fill([
                'processor_id' => $request->get('processor_id'),
                'processed_at' => $request->get('processed_at'),
                'connection_name' => $request->get('connection_name'),
                'connection_phone' => $request->get('connection_phone'),
                'type' => $request->get('type'),
                'direction' => 'IN',
                'serial_number' => Code::makeSerialNumber('IN')
            ])->saveOrFail();

            # 插入入所单整件实例
            $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
            $warehouseReportEntireInstance->fill([
                'warehouse_report_serial_number' => $warehouseReport->serial_number,
                'entire_instance_identity_code' => $newEntireInstanceIdentityCode,
                'un_cycle_fix_count' => 1,
            ])->saveOrFail();

            if ($request->get('auto_insert_fix_workflow')) {
                $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW');
                # 插入检修单
                $fixWorkflow = new FixWorkflow;
                $fixWorkflow->fill([
                    'entire_instance_identity_code' => $newEntireInstanceIdentityCode,
                    'warehouse_report_serial_number' => $warehouseReport->serial_number,
                    'status' => 'FIXING',
                    'processor_id' => $request->get('processor_id'),
                    'serial_number' => $fixWorkflowSerialNumber,
                    'stage' => 'UNFIX',
                ])->saveOrFail();

                # 修改整件实例中检修单序列号和状态
                $entireInstance->fill(['fix_workflow_serial_number' => $fixWorkflowSerialNumber, 'status' => 'FIXING'])->saveOrFail();
            }
        });
        return $newEntireInstanceIdentityCode;
    }

    /**
     * 维修单：出所
     * @param Request $request 表单数据
     * @param FixWorkflow $fixWorkflow 检修单数据
     * @throws \Throwable
     */
    public function fixWorkflowOutOnce(Request $request, FixWorkflow $fixWorkflow)
    {
        DB::transaction(function () use ($request, $fixWorkflow) {
            $type = $request->get('maintain_station_name', null) . $request->get('maintain_location_code', null) ? 'INSTALLED' : 'INSTALLING';
            # 生成新的整件流水号
            $newEntireInstanceSerialNumber = Code::makeEntireInstanceSerialNumber($fixWorkflow->EntireInstance->EntireModel->unique_code);

            # 修改整件数据
            $entireInstance = EntireInstance::where('identity_code', $fixWorkflow->entire_instance_identity_code)->firstOrFail();
            $entireInstance->fill($request->all())->saveOrFail();

            # 生成出所单
            $newWarehouseReportSerialNumber = Code::makeSerialNumber('OUT');
            $warehouseReport = new WarehouseReport;
            $warehouseReport->fill([
                'processor_id' => $request->get('processor_id'),
                'processed_at' => $request->get('processed_at'),
                'connection_name' => $request->get('connection_name'),
                'connection_phone' => $request->get('connection_phone'),
                'type' => 'INSTALL',
                'direction' => 'OUT',
                'serial_number' => $newWarehouseReportSerialNumber,
            ])
                ->saveOrFail();

            # 生成出所设备记录
            $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
            $warehouseReportEntireInstance->fill([
                'warehouse_report_serial_number' => $newWarehouseReportSerialNumber,
                'entire_instance_identity_code' => $fixWorkflow->entire_instance_identity_code,
            ])->saveOrFail();

            # 修改整件状态和最后一次出所单流水号
            $nextFixingData = \App\Facades\EntireInstance::nextFixingTime($fixWorkflow->EntireInstance);  # 计算最后一次检修状态
//            if ($fixWorkflow->EntireInstance->EntireModel->fix_cycle_value > 0) {
//                if ($fixWorkflow->EntireInstance->fix_cycle_value > 0) {
//                    # 使用整件实例的周期
//                    $fixCycleUnit = EntireInstance::flipFixCycleUnit($fixWorkflow->EntireInstance->fix_cycle_unit);
//                    $nextFixingTime = strtotime("+{$fixWorkflow->EntireInstance->fix_cycle_value} {$fixCycleUnit}");
//                } else {
//                    # 使用整件型号的周期
//                    $fixCycleUnit = EntireModel::flipFixCycleUnit($fixWorkflow->EntireInstance->EntireModel->fix_cycle_unit);
//                    $nextFixingTime = strtotime("+{$fixWorkflow->EntireInstance->EntireModel->fix_cycle_value} {$fixCycleUnit}");
//                }
//                $nextFixingMonth = date('Y-m-01', $nextFixingTime);
//                $nextFixingDay = date('Y-m-d', $nextFixingTime);
//                $nextAutoMakingFixWorkflowTime = strtotime("-2 month", $nextFixingTime);
//                $nextAutoMakingFixWorkflowAt = date('Y-m-01', $nextAutoMakingFixWorkflowTime);
//                $nextFixingData = [
//                    'next_auto_making_fix_workflow_time' => $nextAutoMakingFixWorkflowTime,
//                    'next_fixing_time' => $nextFixingTime,
//                    'next_auto_making_fix_workflow_at' => $nextAutoMakingFixWorkflowAt,
//                    'next_fixing_month' => $nextFixingMonth,
//                    'next_fixing_day' => $nextFixingDay
//                ];
//            }

            $fillData = [
                'status' => $type,
                'in_warehouse' => false,
                'last_warehouse_report_serial_number_by_out' => $warehouseReport->serial_number,
                'serial_number' => $newEntireInstanceSerialNumber,
                'maintain_station_name' => $request->get('maintain_station_name'),
                'maintain_location_code' => $request->get('maintain_location_code'),
                'last_installed_time' => Time::fromDatetime($request->get('processed_at'))->toTimestamp(),
            ];

            if ($nextFixingData) {
                $fillData = array_merge($fillData, $nextFixingData);
            }
            $fixWorkflow->EntireInstance->fill($fillData)->saveOrFail();

            # 修改部件状态
            DB::table('part_instances')
                ->where('entire_instance_identity_code', $fixWorkflow->EntireInstance->identity_code)
                ->update([
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => $type,
                    'entire_instance_serial_number' => $newEntireInstanceSerialNumber
                ]);

            # 生成整件操作日志
            $entireInstanceLog = new EntireInstanceLog;
            $entireInstanceLog->fill([
                'name' => '出所安装',
                'description' => $type == 'INSTALLED' ? "已确定位置：{$request->maintain_station_name} {$request->maintain_location_code}" : '未确定位置',
                'entire_instance_identity_code' => $fixWorkflow->entire_instance_identity_code,
            ])
                ->saveOrFail();
        });
    }

    /**
     * 返厂维修出所
     * @param Request $request
     * @param FixWorkflow $fixWorkflow
     */
    public function returnFactoryOutOnce(Request $request, FixWorkflow $fixWorkflow)
    {
        DB::transaction(function () use ($request, $fixWorkflow) {
            # 修改检修单状态
            $fixWorkflow->fill(['status' => 'RETURN_FACTORY'])->saveOrFail();

            # 修改整件状态
            $fixWorkflow->EntireInstance->fill(['status' => 'RETURN_FACTORY'])->saveOrFail();

            # 修改部件状态
            DB::table('part_instances')->where('entire_instance_identity_code', $fixWorkflow->EntireInstance->identity_code)->update(['status' => 'RETURN_FACTORY']);

            # 生成整件操作日志
            $entireInstanceLog = new EntireInstanceLog();
            $entireInstanceLog->fill([
                'name' => '返厂维修',
                'description' => $request->get('description'),
                'entire_instance_identity_code' => $fixWorkflow->EntireInstance->identity_code,
            ])
                ->saveOrFail();

            # 生成入所单
            $warehouseReport = new WarehouseReport;
            $warehouseReport->fill([
                'processor_id' => $request->get('processor_id'),
                'processed_at' => $request->get('processed_at'),
                'connection_name' => $request->get('connection_name'),
                'connection_phone' => $request->get('connection_phone'),
                'type' => 'RETURN_FACTORY',
                'direction' => 'OUT',
                'serial_number' => $newWarehouseReportSerialNumber = Code::makeSerialNumber('OUT'),
            ])
                ->saveOrFail();

            # 生成出所设备记录
            $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
            $warehouseReportEntireInstance->fill([
                'warehouse_report_serial_number' => $newWarehouseReportSerialNumber,
                'entire_instance_identity_code' => $fixWorkflow->entire_instance_identity_code,
            ])->saveOrFail();
        });
    }

    /**
     * 返厂入所
     * @param Request $request
     * @param FixWorkflow $fixWorkflow
     */
    public function factoryReturnInOnce(Request $request, FixWorkflow $fixWorkflow)
    {
        DB::transaction(function () use ($request, $fixWorkflow) {
            # 修改检修单状态
            $fixWorkflow->fill(['status' => 'FACTORY_RETURN'])->saveOrFail();

            # 修改整件状态
            $fixWorkflow->EntireInstance->fill(['status' => 'FACTORY_RETURN'])->saveOrFail();

            # 修改部件状态
            DB::table('part_instances')->where('entire_instance_identity_code', $fixWorkflow->EntireInstance->identity_code)->update(['status' => 'FACTORY_RETURN']);

            # 生成整件操作日志
            $entireInstanceLog = new EntireInstanceLog();
            $entireInstanceLog->fill([
                'name' => '返厂入所',
                'description' => $request->get('description'),
                'entire_instance_identity_code' => $fixWorkflow->EntireInstance->identity_code,
            ])
                ->saveOrFail();

            # 生成入所单
            $warehouseReport = new WarehouseReport;
            $warehouseReport->fill([
                'processor_id' => $request->get('processor_id'),
                'processed_at' => $request->get('processed_at'),
                'connection_name' => $request->get('connection_name'),
                'connection_phone' => $request->get('connection_phone'),
                'type' => 'FACTORY_RETURN',
                'direction' => 'IN',
                'serial_number' => $newWarehouseReportSerialNumber = Code::makeSerialNumber('IN'),
            ])
                ->saveOrFail();

            # 生成出所设备记录
            $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
            $warehouseReportEntireInstance->fill([
                'warehouse_report_serial_number' => $newWarehouseReportSerialNumber,
                'entire_instance_identity_code' => $fixWorkflow->entire_instance_identity_code,
            ])->saveOrFail();
        });
    }

    /**
     * 维修入所
     * @param Request $request
     * @param EntireInstance $entireInstance
     */
    public function fixingInOnce(Request $request, EntireInstance $entireInstance)
    {
        DB::transaction(function () use ($request, $entireInstance) {
            # 修改整件状态
            $entireInstance->fill([
                'status' => 'FIXING',
                'un_cycle_fix_count' => $entireInstance->un_cycle_fix_count + 1,  # 非周期检修的次数+1
            ])
                ->saveOrFail();

            # 修改部件状态
            DB::table('part_instances')->where('entire_instance_identity_code', $entireInstance->identity_code)->update(['status' => 'FIXING']);

            # 生成整件操作日志
            $entireInstanceLog = new EntireInstanceLog();
            $entireInstanceLog->fill([
                'name' => '检修单：入所',
                'entire_instance_identity_code' => $entireInstance->identity_code,
            ])
                ->saveOrFail();

            # 生成入所单
            $warehouseReport = new WarehouseReport;
            $warehouseReport->fill([
                'processor_id' => $request->get('processor_id'),
                'processed_at' => $request->get('processed_at'),
                'connection_name' => $request->get('connection_name'),
                'connection_phone' => $request->get('connection_phone'),
                'type' => 'FIXING',
                'direction' => 'IN',
                'serial_number' => $newWarehouseReportSerialNumber = Code::makeSerialNumber('IN'),
            ])
                ->saveOrFail();

            # 生成入所设备记录
            $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
            $warehouseReportEntireInstance->fill([
                'warehouse_report_serial_number' => $newWarehouseReportSerialNumber,
                'entire_instance_identity_code' => $entireInstance->identity_code,
            ])->saveOrFail();
        });
    }

    /**
     * 维修单：入所
     * @param Request $request
     * @param FixWorkflow $fixWorkflow
     */
    public function fixWorkflowInOnce(Request $request, FixWorkflow $fixWorkflow)
    {
        DB::transaction(function () use ($request, $fixWorkflow) {
            # 修改整件状态
            $fixWorkflow->EntireInstance->fill([
                'status' => 'FIXING',
                'in_warehouse' => false,
                'fix_workflow_serial_number' => null,
                'un_cycle_fix_count' => $fixWorkflow->EntireInstance->un_cycle_fix_count + 1,  # 非周期检修的次数+1
            ])->saveOrFail();

            # 修改部件状态
            DB::table('part_instances')->where('entire_instance_identity_code', $fixWorkflow->EntireInstance->identity_code)->update(['status' => 'FIXING']);

            # 生成整件操作日志
            $entireInstanceLog = new EntireInstanceLog();
            $entireInstanceLog->fill([
                'name' => '检修单：入所',
                'entire_instance_identity_code' => $fixWorkflow->EntireInstance->identity_code,
            ])
                ->saveOrFail();

            # 生成入所单
            $warehouseReport = new WarehouseReport;
            $warehouseReport->fill([
                'processor_id' => $request->get('processor_id'),
                'processed_at' => $request->get('processed_at'),
                'connection_name' => $request->get('connection_name'),
                'connection_phone' => $request->get('connection_phone'),
                'type' => 'FIXING',
                'direction' => 'IN',
                'serial_number' => $newWarehouseReportSerialNumber = Code::makeSerialNumber('IN'),
            ])
                ->saveOrFail();

            # 生成入所设备记录
            $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
            $warehouseReportEntireInstance->fill([
                'warehouse_report_serial_number' => $newWarehouseReportSerialNumber,
                'entire_instance_identity_code' => $fixWorkflow->entire_instance_identity_code,
            ])->saveOrFail();
        });
    }

    /**
     * 批量入所
     * @param Collection $warehouseBatchReports
     * @return array
     */
    public function inBatch(Collection $warehouseBatchReports)
    {
        $repeat = [];
        DB::transaction(function () use ($warehouseBatchReports, &$repeat) {
            $i = 0;

            foreach ($warehouseBatchReports as $warehouseBatchReport) {
                if (array_flip(EntireInstance::$STATUS)[$warehouseBatchReport->EntireInstance->status] == 'FIXING') {
                    $repeat[] = $warehouseBatchReport->EntireInstance;
                    continue;
                }

                # 修改整件状态
                $warehouseBatchReport->EntireInstance->fill([
                    'status' => 'FIXING',
                    'un_cycle_fix_count' => $warehouseBatchReport->EntireInstance->un_cycle_fix_count + 1,
                ])
                    ->saveOrFail();

                # 修改部件状态
                DB::table('part_instances')->where('entire_instance_identity_code', $warehouseBatchReport->entire_instance_identity_code)->update(['status' => 'FIXING']);

                # 生成整件操作日志
                $entireInstanceLog = new EntireInstanceLog();
                $entireInstanceLog->fill([
                    'name' => '检修单：入所',
                    'entire_instance_identity_code' => $warehouseBatchReport->entire_instance_identity_code,
                ])
                    ->saveOrFail();

                # 生成入所单
                $warehouseReport = new WarehouseReport;
                $warehouseReport->fill([
                    'processor_id' => session()->get('account.id'),
                    'processed_at' => date('Y-m-d'),
//                'connection_name' => '',
//                'connection_phone' => '',
                    'type' => 'FIXING',
                    'direction' => 'IN',
                    'serial_number' => $newWarehouseReportSerialNumber = Code::makeSerialNumber('IN') . ++$i,
                ])
                    ->saveOrFail();

                # 生成入所设备记录
                $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
                $warehouseReportEntireInstance->fill([
                    'warehouse_report_serial_number' => $newWarehouseReportSerialNumber,
                    'entire_instance_identity_code' => $warehouseBatchReport->entire_instance_identity_code,
                ])->saveOrFail();
            }
        });
        return $repeat;
    }

    /**
     * 单设备入所
     * @param Request $request
     * @param EntireInstance $entireInstance
     */
    public function inOnce(Request $request, EntireInstance $entireInstance)
    {
        DB::transaction(function () use ($request, $entireInstance) {
            # 修改整件状态，去掉检修单
            $entireInstance->fill([
                'status' => 'FIXING',
                'in_warehouse' => false,
                'fix_workflow_serial_number' => null,
                'un_cycle_fix_count' => $entireInstance->un_cycle_fix_count + 1,
                'source' => $request->get('source'),
                'source_crossroad_number' => $request->get('source_crossroad_number'),
                'source_traction' => $request->get('source_traction'),
                'forecast_install_at' => $request->get('forecast_install_at'),
            ])->saveOrFail();

            # 修改部件状态
            DB::table('part_instances')->where('entire_instance_identity_code', $entireInstance->identity_code)->update(['status' => 'FIXING']);

            # 生成整件操作日志
            $entireInstanceLog = new EntireInstanceLog();
            $entireInstanceLog->fill([
                'name' => '入所',
                'entire_instance_identity_code' => $entireInstance->identity_code,
            ])
                ->saveOrFail();

            # 生成入所单
            $warehouseReport = new WarehouseReport;
            $warehouseReport->fill([
                'processor_id' => $request->get('processor_id'),
                'processed_at' => $request->get('processed_at'),
                'connection_name' => $request->get('connection_name'),
                'connection_phone' => $request->get('connection_phone'),
                'type' => 'FIXING',
                'direction' => 'IN',
                'serial_number' => $newWarehouseReportSerialNumber = Code::makeSerialNumber('IN'),
            ])
                ->saveOrFail();

            # 生成入所设备记录
            $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
            $warehouseReportEntireInstance->fill([
                'warehouse_report_serial_number' => $newWarehouseReportSerialNumber,
                'entire_instance_identity_code' => $entireInstance->identity_code,
            ])->saveOrFail();
        });
    }

    /**
     * 单设备出所
     * @param Request $request
     * @param EntireInstance $entireInstance
     */
    public function outOnce(Request $request, EntireInstance $entireInstance)
    {
        DB::transaction(function () use ($request, $entireInstance) {
            $type = $request->get('maintain_station_name', null) . $request->get('maintain_location_code', null) ? 'INSTALLED' : 'INSTALLING';
            # 生成新的整件流水号
            $newEntireInstanceSerialNumber = Code::makeEntireInstanceSerialNumber($entireInstance->EntireModel->unique_code);

            # 修改整件数据
            $entireInstance = EntireInstance::where('identity_code', $entireInstance->identity_code)->firstOrFail();
            $entireInstance->fill($request->all())->saveOrFail();

            # 生成出所单
            $newWarehouseReportSerialNumber = Code::makeSerialNumber('OUT');
            $warehouseReport = new WarehouseReport;
            $warehouseReport->fill([
                'processor_id' => $request->get('processor_id'),
                'processed_at' => $request->get('processed_at'),
                'connection_name' => $request->get('connection_name'),
                'connection_phone' => $request->get('connection_phone'),
                'type' => 'INSTALL',
                'direction' => 'OUT',
                'serial_number' => $newWarehouseReportSerialNumber,
            ])
                ->saveOrFail();
            # 生成出所设备记录
            $warehouseReportEntireInstance = new WarehouseReportEntireInstance;
            $warehouseReportEntireInstance->fill([
                'warehouse_report_serial_number' => $newWarehouseReportSerialNumber,
                'entire_instance_identity_code' => $entireInstance->identity_code,
            ])->saveOrFail();

            # 修改整件状态和最后一次出所单流水号
//            $fixCycleUnit = EntireModel::flipFixCycleUnit($entireInstance->EntireModel->fix_cycle_unit);
//            $nextFixingTime = strtotime("+{$entireInstance->EntireModel->fix_cycle_value} {$fixCycleUnit}");
//            $nextFixingMonth = date('Y-m-01', $nextFixingTime);
//            $nextFixingDay = date('Y-m-d', $nextFixingTime);
//            $nextAutoMakingFixWorkflowTime = strtotime("-2 month", $nextFixingTime);
//            $nextAutoMakingFixWorkflowAt = date('Y-m-01', $nextAutoMakingFixWorkflowTime);
//            $entireInstance->fill([
//                'status' => $type,
//                'in_warehouse' => false,
//                'last_warehouse_report_serial_number_by_out' => $warehouseReport->serial_number,
//                'serial_number' => $newEntireInstanceSerialNumber,
//                'maintain_station_name' => $request->get('maintain_station_name'),
//                'maintain_location_code' => $request->get('maintain_location_code'),
//                'last_installed_time' => Time::fromDatetime($request->get('processed_at'))->toTimestamp(),
//                'next_auto_making_fix_workflow_time' => $nextAutoMakingFixWorkflowTime,
//                'next_fixing_time' => $nextFixingTime,
//                'next_auto_making_fix_workflow_at' => $nextAutoMakingFixWorkflowAt,
//                'next_fixing_month' => $nextFixingMonth,
//                'next_fixing_day' => $nextFixingDay
//            ])
//                ->saveOrFail();
            $nextFixingData = \App\Facades\EntireInstance::nextFixingTime($entireInstance);
            $entireInstance->fill(array_merge($nextFixingData, [
                    'status' => $type,
                    'in_warehouse' => false,
                    'last_warehouse_report_serial_number_by_out' => $warehouseReport->serial_number,
                    'serial_number' => $newEntireInstanceSerialNumber,
                    'maintain_station_name' => $request->get('maintain_station_name'),
                    'maintain_location_code' => $request->get('maintain_location_code'),
                    'last_installed_time' => Time::fromDatetime($request->get('processed_at'))->toTimestamp()
                ])
            )
                ->saveOrFail();

            # 修改部件状态
            DB::table('part_instances')
                ->where('entire_instance_identity_code', $entireInstance->identity_code)
                ->update([
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => $type,
                    'entire_instance_serial_number' => $newEntireInstanceSerialNumber
                ]);

            # 生成整件操作日志
            $entireInstanceLog = new EntireInstanceLog;
            $entireInstanceLog->fill([
                'name' => '出所安装',
                'description' => $type == 'INSTALLED' ? "已确定位置：{$request->maintain_station_name} {$request->maintain_location_code}" : '未确定位置',
                'entire_instance_identity_code' => $entireInstance->identity_code,
            ])
                ->saveOrFail();
        });
    }
}
