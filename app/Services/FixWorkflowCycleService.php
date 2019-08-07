<?php

namespace App\Services;

use App\Facades\Code;
use App\Model\Category;
use App\Model\EntireInstance;
use App\Model\EntireModel;
use App\Model\FixWorkflow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FixWorkflowCycleService
{
    /**
     * 获取基础信息
     * @param int $year
     * @param int $month
     */
    public function getBasicInfo(int $year, int $month)
    {
        # 获取设备型号
        $categories = Category::pluck('name', 'unique_code');
        # 获取对应的型号
        $entireModels = [];
        foreach ($categories as $categoryKey => $categoryValue) {
            $entireModels[$categoryKey] = EntireModel::where('category_unique_code', $categoryKey)->pluck('name', 'unique_code');
        }

        $this->saveFile($year, $month, 'categories.json', $categories);
        $this->saveFile($year, $month, 'entireModels.json', $entireModels);

        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        if (is_file(storage_path('app/fixWorkflow/dateList.json'))) {
            $dateList = json_decode(file_get_contents(storage_path('app/fixWorkflow/dateList.json')), true);
            $dateList[] = "{$year}-{$month}";
            if (count($dateList) > 1) $dateList = array_unique($dateList);
        } else {
            $dateList = ["{$year}-{$month}"];
        }

        Storage::disk('local')->put('fixWorkflow/dateList.json', json_encode($dateList, 256));
    }

    /**
     * 保存文件
     * @param int $year
     * @param int $month
     * @param string $fileName
     * @param $content
     */
    private function saveFile(int $year, int $month, string $fileName, $content)
    {
        Storage::disk('local')->put($this->savePath($year, $month, $fileName), json_encode($content, 256));
    }

    /**
     * 获取保存文件地址
     * @param int $year
     * @param int $month
     * @param string $fileName
     * @return string
     */
    private function savePath(int $year, int $month, string $fileName): string
    {
        $monthDay = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        return "/fixWorkflow/{$monthDay}/{$fileName}";
    }

    /**
     * 递归删除文件夹
     * @param $dir
     * @return bool
     */
    public function deleteDir($dir)
    {
        if (!$handle = @opendir($dir)) {
            return false;
        }
        while (false !== ($file = readdir($handle))) {
            if ($file !== "." && $file !== "..") {       //排除当前目录与父级目录
                $file = $dir . '/' . $file;
                if (is_dir($file)) {
                    $this->deleteDir($file);
                } else {
                    @unlink($file);
                }
            }

        }
        @rmdir($dir);
    }

    /**
     * 获取上月已修数量（按照类型、型号）
     * @param int $year
     * @param int $month
     */
    public function getLastMonthFixedCount(int $year, int $month)
    {
        # 获取上月时间
        $timestamp = mktime(null, null, null, $month, 1, $year);
        $firstTimestamp = strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01');
        $lastTimestamp = strtotime("+1 month -1 day", $firstTimestamp);

        $entireModelsFile = $this->readFile($year, $month, 'entireModels.json');
        $lastMonthFixedCount = [];
        foreach ($entireModelsFile as $categoryUniqueCode => $entireModels) {
            foreach ($entireModels as $entireModelUniqueCode => $entireModelName) {
                $count = FixWorkflow::with(['EntireInstance'])
                    ->whereHas('EntireInstance', function ($query) use ($entireModelUniqueCode) {
                        $query->where('entire_model_unique_code', $entireModelUniqueCode);
                    })
                    ->whereBetween('updated_at', [date('Y-m-d', $firstTimestamp), date('Y-m-d', $lastTimestamp)])
                    ->where('status', 'FIXED')
                    ->count('id');
                $lastMonthFixedCount[$entireModelUniqueCode] = $count;
            }
        }

        $this->saveFile($year, $month, 'lastMonthFixedCount.json', $lastMonthFixedCount);
    }

    /**
     * 读取文件
     * @param int $year
     * @param int $month
     * @param string $fileName
     * @return array
     */
    private function readFile(int $year, int $month, string $fileName): array
    {
        return json_decode(file_get_contents($this->readPath($year, $month, $fileName)), true);
    }

    /**
     * 获取读取文件路径
     * @param int $year
     * @param int $month
     * @param string $fileName
     * @return string
     */
    private function readPath(int $year, int $month, string $fileName): string
    {
        $monthDay = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        return storage_path("app/fixWorkflow/{$monthDay}/{$fileName}");
    }

    /**
     * 获取本月应修数量（按照类型、型号）
     * @param int $year
     * @param int $month
     */
    public function getCurrentMonthGoingToFixCount(int $year, int $month)
    {
        # 获取本月时间
        $firstTimestamp = mktime(null, null, null, $month, 1, $year);
        $lastTimestamp = strtotime("+1 month -1 day", $firstTimestamp);

        $entireModelsFile = $this->readFile($year, $month, 'entireModels.json');
        $currentMonthFixingCount = [];
        foreach ($entireModelsFile as $categoryUniqueCode => $entireModels) {
            foreach ($entireModels as $entireModelUniqueCode => $entireModelName) {
                $entireInstance = EntireInstance::where('entire_model_unique_code', $entireModelUniqueCode)
                    ->whereIn('status', ['INSTALLED', 'INSTALLING'])
                    ->whereBetween('next_fixing_time', [$firstTimestamp, $lastTimestamp])
                    ->count('id');
                $currentMonthFixingCount[$entireModelUniqueCode] = $entireInstance ?: 0;
            }
        }
        $this->saveFile($year, $month, 'currentMonthFixingCount.json', $currentMonthFixingCount);
    }

    /**
     * 获取用于自动生成检修单的整件实例身份码
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getEntireInstanceIdentityCodesForGoingToAutoMakeFixWorkflow(int $year, int $month): Collection
    {
        # 获取本月时间
        $firstTimestamp = mktime(null, null, null, $month, 1, $year);
        $lastTimestamp = strtotime("+1 month -1 day", $firstTimestamp);
        $entireInstances = EntireInstance::with(['EntireModel'])->whereBetween('next_auto_making_fix_workflow_time', [$firstTimestamp, $lastTimestamp]);
//        $entireInstances = EntireInstance::with(['EntireModel']);
        $this->saveFile($year, $month, 'entireInstanceIdentityCodesForGoingToAutoMakeFixWorkflow.json', $entireInstances->get());
        return $entireInstances->get();
    }

    /**
     * 自动生成检修单
     * @param Collection $entireInstances
     */
    public function autoMakeFixWorkflow(Collection $entireInstances)
    {
        $fixWorkflows = [];
        $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW');
        $i = 0;

        DB::transaction(function () use ($entireInstances, $fixWorkflowSerialNumber, $fixWorkflows, $i) {
            $currentDatetime = date('Y-m-d');
            foreach ($entireInstances as $entireInstance) {
                $i += 1;
                $fixWorkflows[] = [
                    'created_at' => $currentDatetime,
                    'updated_at' => $currentDatetime,
                    'status' => 'UNFIX',
                    'entire_instance_identity_code' => $entireInstance->identity_code,
                    'serial_number' => $fixWorkflowSerialNumber . "_{$i}",
                    'note' => '周期自动生成',
                    'maintain_station_name' => $entireInstance->maintain_station_name,
                    'maintain_location_name' => $entireInstance->maintain_location_code,
                    'is_cycle' => true,
                ];
            }
            DB::table('fix_workflows')->insert($fixWorkflows);
        });
    }
}
