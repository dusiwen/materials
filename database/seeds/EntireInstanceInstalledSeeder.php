<?php

use App\Facades\Code;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntireInstanceInstalledSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5000; $i++) {
            $currentDatetime = date('Y-m-d H:i:s');

            $entireModelIdCodes = [
                'ZD6-A',
                'ZD6-D',
            ];

            $categoryUniqueCode = "S03";
            $entireModelUniqueCode = "01";

            $identityCode = \App\Facades\Code::makeEntireInstanceIdentityCode($entireModelUniqueCode);

            # 安装出库
            # 随机安装时间
            $randInstalledDay = rand(1, 4);
            $lastInstalledTime = strtotime("-{$randInstalledDay} month");
            $nextFixingTime = strtotime("+3 month", $lastInstalledTime);
            $nextFixingMonth = date('Y-m-01', $nextFixingTime);
            $nextFixingDay = date('Y-m-d', $nextFixingTime);
            $nextAutoMakingFixWorkflowTime = strtotime("-2 month", $nextFixingTime);
            $nextAutoMakingFixWorkflowAt = date('Y-m-01', $nextAutoMakingFixWorkflowTime);

            $entireInstance = [
                'created_at' => $currentDatetime,
                'updated_at' => $currentDatetime,
                'entire_model_unique_code' => $entireModelUniqueCode,
                'entire_model_id_code' => $categoryUniqueCode == "S03" ? $entireModelIdCodes[rand(0, 1)] : null,
                'status' => 'INSTALLED',
                'factory_name' => '西安铁路信号设备有限责任公司',
                'factory_device_code' => $identityCode,
                'fix_cycle_unit' => 'YEAR',
                'fix_cycle_value' => 3,
//                'maintain_station_name' => '十里冲',
//                'maintain_location_code' => rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9),
                'last_installed_time' => $lastInstalledTime,
                'serial_number' => $identityCode,
                'identity_code' => $identityCode,
                'category_unique_code' => $categoryUniqueCode,
                'next_auto_making_fix_workflow_time' => $nextAutoMakingFixWorkflowTime,
                'next_fixing_time' => $nextFixingTime,
                'next_auto_making_fix_workflow_at' => $nextAutoMakingFixWorkflowAt,
                'next_fixing_month' => $nextFixingMonth,
                'next_fixing_day' => $nextFixingDay
            ];
            DB::table('entire_instances')->insert($entireInstance);

            $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW') . strval(++$i);
            $fixWorkflow = [
                'created_at' => $entireInstance['created_at'],
                'updated_at' => $entireInstance['created_at'],
                'entire_instance_identity_code' => $entireInstance['identity_code'],
                'warehouse_report_serial_number' => null,
                'status' => 'FIXING',
                'processor_id' => rand(1, 21),
                'expired_at' => null,
                'id_by_failed' => null,
                'serial_number' => $fixWorkflowSerialNumber,
                'note' => null,
                'processed_times' => 0,
                'stage' => 'PART',
                'is_cycle' => false,
                'entire_fix_after_count' => 0,
                'part_fix_after_count' => 0,
            ];
            DB::table('fix_workflows')->insert($fixWorkflow);
        }
    }
}
