<?php

namespace App\Services;

use App\Model\EntireInstance;
use App\Model\EntireModel;
use Illuminate\Support\Facades\DB;

class EntireInstanceService
{
    /**
     * 自增设备实例总数
     * @param string $entireModelUniqueCode
     * @return int
     */
    public function incCount(string $entireModelUniqueCode): int
    {
        $entireFixedCountDB = DB::table('entire_instance_counts')->where('entire_model_unique_code', $entireModelUniqueCode)->first(['count']);
        if ($entireFixedCountDB) {
            $entireFixedCount = $entireFixedCountDB ? $entireFixedCountDB->count : 0;
            DB::table('entire_instance_counts')->where('entire_model_unique_code', $entireModelUniqueCode)->update(['count' => $entireFixedCount + 1]);
            return $entireFixedCount + 1;
        } else {
            DB::table('entire_instance_counts')->insert(['entire_model_unique_code' => $entireModelUniqueCode, 'count' => 1]);
            return 1;
        }
    }

    /**
     * 记录设备维修总数
     * @param string $entireModelUniqueCode
     * @return int
     */
    public function incFixedCount(string $entireModelUniqueCode): int
    {
        $entireFixedCountDB = DB::table('entire_fixed_counts')->where('entire_model_unique_code', $entireModelUniqueCode)->where('year', date('Y'))->first(['count']);
        if ($entireFixedCountDB) {
            $entireFixedCount = $entireFixedCountDB ? $entireFixedCountDB->count : 0;
            DB::table('entire_fixed_counts')->where('entire_model_unique_code', $entireModelUniqueCode)->where('year', date('Y'))->update(['count' => $entireFixedCount + 1]);
            return $entireFixedCount + 1;
        } else {
            DB::table('entire_fixed_counts')->insert(['entire_model_unique_code' => $entireModelUniqueCode, 'year' => date('Y'), 'count' => 1]);
            return 1;
        }
    }

    /**
     * 计算下一次检修时间
     * @param EntireInstance $entireInstance
     * @param int $fixCycleValue
     * @param string $fixCycleUnit
     * @return array
     */
    public function nextFixingTime(EntireInstance $entireInstance, int $fixCycleValue = 0, string $fixCycleUnit = 'YEAR'): array
    {
        # 修改整件状态和最后一次出所单流水号
        $nextFixingData = null;
        $nextFixingData = [];
        if (floatval($fixCycleValue) == 0) {
            if ($entireInstance->EntireModel->fix_cycle_value > 0) {
                if ($entireInstance->EntireInstance->fix_cycle_value > 0) {
                    # 使用整件实例的周期
                    $fixCycleUnit = EntireInstance::flipFixCycleUnit($entireInstance->EntireInstance->fix_cycle_unit);
                    $nextFixingTime = strtotime("+{$entireInstance->EntireInstance->fix_cycle_value} {$fixCycleUnit}");
                } else {
                    # 使用整件型号的周期
                    $fixCycleUnit = EntireModel::flipFixCycleUnit($entireInstance->EntireInstance->EntireModel->fix_cycle_unit);
                    $nextFixingTime = strtotime("+{$entireInstance->EntireInstance->EntireModel->fix_cycle_value} {$fixCycleUnit}");
                }
            }
        } else {
            $nextFixingTime = strtotime("+{$fixCycleValue} {$fixCycleUnit}");
        }

        $nextFixingMonth = date('Y-m-01', $nextFixingTime);
        $nextFixingDay = date('Y-m-d', $nextFixingTime);
        $nextAutoMakingFixWorkflowTime = strtotime("-2 month", $nextFixingTime);
        $nextAutoMakingFixWorkflowAt = date('Y-m-01', $nextAutoMakingFixWorkflowTime);
        $nextFixingData = [
            'next_auto_making_fix_workflow_time' => $nextAutoMakingFixWorkflowTime,
            'next_fixing_time' => $nextFixingTime,
            'next_auto_making_fix_workflow_at' => $nextAutoMakingFixWorkflowAt,
            'next_fixing_month' => $nextFixingMonth,
            'next_fixing_day' => $nextFixingDay
        ];
        return $nextFixingData;
    }
}
