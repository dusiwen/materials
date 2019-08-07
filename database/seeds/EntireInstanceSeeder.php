<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntireInstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5000; $i++) {
            $this->insert();
        }
    }

    public function insert()
    {
        $currentDatetime = date('Y-m-d H:i:s');

        $entireModelIdCodes = [
            'ZD6-A',
            'ZD6-D',
        ];

        $entireInstanceStatuses = [
            'BUY_IN',
            'FIXING',
            'FIXED',
            'RETURN_FACTORY',
            'FACTORY_RETURN',
            'SCRAP',
        ];

        $type = "Q01";
        switch ($type) {
            case "Q01":  # 转辙机
                $entireModelUniqueCode = "0101";
                $categoryUniqueCode = "Q01";
                break;
            case "S03":  # 继电器
            default:
                $entireModelUniqueCode = "01";
                $categoryUniqueCode = "S03";
                break;
        }

        $identityCode = \App\Facades\Code::makeEntireInstanceIdentityCode($entireModelUniqueCode);

        # 随机是否安装
        if (rand(0, 1)) {
            # 安装出库
            # 随机安装时间
            $randInstalledDay = rand(1, 4);
            $lastInstalledTime = strtotime("-{$randInstalledDay} month");
            $nextFixingTime = strtotime("+3 month", $lastInstalledTime);
            $nextFixingMonth = date('Y-m-01', $nextFixingTime);
            $nextFixingDay = date('Y-m-d', $nextFixingTime);
            $nextAutoMakingFixWorkflowTime = strtotime("-2 month", $nextFixingTime);
            $nextAutoMakingFixWorkflowAt = date('Y-m-01', $nextAutoMakingFixWorkflowTime);

            $insertData = [
                'created_at' => $currentDatetime,
                'updated_at' => $currentDatetime,
                'entire_model_unique_code' => $entireModelUniqueCode,
                'entire_model_id_code' => $type == "S03" ? $entireModelIdCodes[rand(0, 1)] : null,
                'status' => 'INSTALLED',
                'factory_name' => '西安铁路信号设备有限责任公司',
                'factory_device_code' => $identityCode,
                'fix_cycle_unit' => 'YEAR',
                'fix_cycle_value' => 3,
                'maintain_station_name' => '十里冲',
                'maintain_location_code' => rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9),
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
        } else {
            # 不安装
            $insertData = [
                'created_at' => $currentDatetime,
                'updated_at' => $currentDatetime,
                'entire_model_unique_code' => $entireModelUniqueCode,
                'entire_model_id_code' => $entireModelIdCodes[rand(0, 1)],
                'status' => 'FIXING',
                'in_warehouse' => false,
                'factory_name' => '太原铁路信号设备有限责任公司',
                'factory_device_code' => $identityCode,
                'fix_cycle_unit' => 'YEAR',
                'fix_cycle_value' => 3,
                'serial_number' => $identityCode,
                'identity_code' => $identityCode,
                'category_unique_code' => $categoryUniqueCode,
            ];
        }
        DB::table('entire_instances')->insert($insertData);
    }
}
