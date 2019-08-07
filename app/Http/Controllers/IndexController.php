<?php

namespace App\Http\Controllers;

use App\Facades\Code;
use App\Http\Requests\Request;
use App\Model\Account;
use App\Model\Category;
use App\Model\EntireInstance;
use App\Model\EntireModel;
use App\Model\FixWorkflow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        /**
//         * 获取左侧快捷按钮的统计
//         * @return array
//         */
//        $shortcutButtonsStatistics = function () {
//            $shortcutButtonsStatisticsCurrentMonthFirst = date("Y-m-01");
//            $shortcutButtonsStatisticsCurrentMonthEndless = Carbon::parse("+1 month -1second")->toDateString();
//            $shortcutButtonsStatistics = [
//                'search' => '',
//                # 当月检修比例
//                'fixWorkflow' => [
//                    'total' => $totalFixWorkflow = intval(FixWorkflow::whereBetween('created_at', [$shortcutButtonsStatisticsCurrentMonthFirst, $shortcutButtonsStatisticsCurrentMonthEndless])->count()),
//                    'completed' => $completedFixWorkflow = intval(FixWorkflow::whereBetween('created_at', [$shortcutButtonsStatisticsCurrentMonthFirst, $shortcutButtonsStatisticsCurrentMonthEndless])->where('status', 'FIXED')->count()),
//                    'proportion' => $completedFixWorkflow > 0 ? $totalFixWorkflow !== $completedFixWorkflow ? intval(round(floatval($completedFixWorkflow / $totalFixWorkflow), 2) * 100) : 100 : 0,
//                ],
//                # 当月新设备
//                'new' => [
//                    'total' => $totalEntireInstance = EntireInstance::whereBetween('created_at', [$shortcutButtonsStatisticsCurrentMonthFirst, $shortcutButtonsStatisticsCurrentMonthEndless])->count(),
//                ],
//                # 当月周期修
//                'fixCycle' => [
//                    'total' => $totalFixCycle = intval(FixWorkflow::whereBetween('created_at', [$shortcutButtonsStatisticsCurrentMonthFirst, $shortcutButtonsStatisticsCurrentMonthEndless])->where('is_cycle', true)->count()),
//                    'completed' => $completedFixCycle = intval(FixWorkflow::whereBetween('created_at', [$shortcutButtonsStatisticsCurrentMonthFirst, $shortcutButtonsStatisticsCurrentMonthEndless])->where('status', 'FIXED')->where('is_cycle', true)->count()),
//                    'proportion' => $completedFixCycle > 0 ? $totalFixCycle !== $completedFixCycle ? intval(round(floatval($completedFixCycle / $totalFixCycle), 2) * 100) : 100 : 0,
//                ],
//                # 当月质量报告
//                'quality' => [],
//                # 当月验收
//                'check' => [
//                    'fixed' => $totalCheck = FixWorkflow::whereBetween('created_at', [$shortcutButtonsStatisticsCurrentMonthFirst, $shortcutButtonsStatisticsCurrentMonthEndless])->where('type', 'FIX')->where('status', 'FIXED')->count(),
//                    'checked' => $completedCheck = FixWorkflow::whereBetween('created_at', [$shortcutButtonsStatisticsCurrentMonthFirst, $shortcutButtonsStatisticsCurrentMonthEndless])->where('type', 'CHECK')->where('status', 'FIXED')->count(),
//                ]];
//            return $shortcutButtonsStatistics;
//        };
//        $shortcutButtonsStatistics = $shortcutButtonsStatistics();
//        if (($shortcutButtonsStatistics['check']['fixed'] + $shortcutButtonsStatistics['check']['checked']) > 0) {
//            if ($shortcutButtonsStatistics['check']['fixed'] != $shortcutButtonsStatistics['check']['checked']) {
//                $shortcutButtonsStatistics['check']['proportion'] = intval(round(intval($shortcutButtonsStatistics['check']['checked']) / intval($shortcutButtonsStatistics['check']['fixed']), 2));
//            } else {
//                $shortcutButtonsStatistics['check']['proportion'] = 100;
//            }
//        }else{
//            $shortcutButtonsStatistics['check']['proportion'] = 0;
//        }
//
//        /**
//         * @return false|string
//         */
//        $deviceDynamicStatus = function () {
//            $categoryUniqueCode = request()->get('categoryUniqueCode', 'S03');
//            $using = EntireInstance::with(['Category'])->where('category_unique_code', $categoryUniqueCode)->whereIn('status', ['INSTALLING', 'INSTALLED'])->count('id');
//            $fixed = EntireInstance::with(['Category'])->where('category_unique_code', $categoryUniqueCode)->where('status', 'FIXED')->where('in_warehouse', false)->count('id');
//            $returnFactory = EntireInstance::with(['Category'])->where('category_unique_code', $categoryUniqueCode)->where('status', 'RETURN_FACTORY')->count('id');
//            $fixing = EntireInstance::with(['Category'])->where('category_unique_code', $categoryUniqueCode)->where('status', 'FIXING')->count('id');
//            $total = $using + $fixed + $fixing + $returnFactory;
//            $aaa = 1111;
//            $deviceDynamicStatus = [
//                ["label" => "在用", "value" => $using],
//                ["label" => "维修", "value" => $fixing],
//                ["label" => "送检", "value" => $aaa],
//                ["label" => "备用", "value" => $fixed]
//            ];
//            return json_encode([$total, $deviceDynamicStatus], 256);
//        };
//        $deviceDynamicStatus = $deviceDynamicStatus();
//
//        /**
//         * 检修工作和计划统计
//         * @return array
//         */
//        $fixedAndFixing = function () {
//            $currentYearMonth = request()->get('fixWorkflowCycleDate', date('Y-m'));
//            if (is_dir(storage_path("app/fixWorkflow/{$currentYearMonth}"))) {
//                $currentMonthFixingCount = json_decode(file_get_contents(storage_path("app/fixWorkflow/{$currentYearMonth}/currentMonthFixingCount.json")), true);
//                $lastMonthFixedCount = json_decode(file_get_contents(storage_path("app/fixWorkflow/{$currentYearMonth}/lastMonthFixedCount.json")), true);
//                $entireModels = EntireModel::pluck('name', 'unique_code');
//                $fixingAndFixed = [];
//                foreach ($currentMonthFixingCount as $key => $item) {
//                    $fixingAndFixed[] = [
//                        "entireModelName" => $entireModels[$key],
//                        "goingToFixing" => $item,
//                        "fixed" => $lastMonthFixedCount[$key]
//                    ];
//                }
//                $fixingAndFixed = json_encode($fixingAndFixed, 256);
//                $fixingAndFixedDateList = json_decode(file_get_contents(storage_path("app/fixWorkflow/dateList.json")), true);
//                return [$fixingAndFixed, $fixingAndFixedDateList];
//            } else {
//                return ["[]", []];
//            }
//        };
//        list($fixingAndFixed, $fixingAndFixedDateList) = $fixedAndFixing();
//
//        /**
//         * 一次修过率
//         * @return false|string
//         */
//        $onlyFixeds = function () {
//            $onlyFixeds = [];
//            foreach (Category::all() as $key => $item) {
//                # 一次修过
//                $onlyFixed = FixWorkflow::with([
//                    'EntireInstance',
//                    'EntireInstance.EntireModel',
//                    'EntireInstance.Category',
//                ])
//                    ->whereHas('EntireInstance.Category', function ($category) use ($item) {
//                        $category->where('unique_code', $item->unique_code);
//                    })
//                    ->where('entire_fix_after_count', 1)
//                    ->where('part_fix_after_count', 1)
//                    ->where('is_cycle', true)
//                    ->count();
//
//                $allFixed = FixWorkflow::with([
//                    'EntireInstance',
//                    'EntireInstance.EntireModel',
//                    'EntireInstance.Category',
//                ])
//                    ->whereHas('EntireInstance.Category', function ($category) use ($item) {
//                        $category->where('unique_code', $item->unique_code);
//                    })
//                    ->where('is_cycle', true)
//                    ->count();
//
//                $onlyFixeds[] = ["name" => $item->name, "value" => $onlyFixed, "all" => $allFixed];
//            }
//            return json_encode($onlyFixeds, 256);
//        };
//        $onlyFixeds = $onlyFixeds();


        //出入库统计->获取出入库时间
        $date1 = date("Y-m-d",time());//获取当前月日
        $date2 = date("Y-m-d",strtotime("-1 day"));//获取前一天月日
        $date3 = date("Y-m-d",strtotime("-2 day"));//获取前两天月日
        //出入库统计->获取入库数量
        $stockinsum1 = DB::table("stockincensus")->where("time",$date1)->get(["sum"])->toArray();//获取当前月日入库数量
        $stockinsum2 = DB::table("stockincensus")->where("time",$date2)->get(["sum"])->toArray();//获取前一天月日入库数量
        $stockinsum3 = DB::table("stockincensus")->where("time",$date3)->get(["sum"])->toArray();//获取前两天月日入库数量
        if (empty($stockinsum1["0"]->sum)){
            $stockinsum1 = "0";
        }else{
            $stockinsum1 = $stockinsum1["0"]->sum;
        }
        if (empty($stockinsum2["0"]->sum)){
            $stockinsum2 = "0";
        }else{
            $stockinsum2 = $stockinsum2["0"]->sum;
        }
        if (empty($stockinsum3["0"]->sum)){
            $stockinsum3 = "0";
        }else{
            $stockinsum3 = $stockinsum3["0"]->sum;
        }
        //出入库统计->获取出库数量
        $stockoutsum1 = DB::table("stockoutcensus")->where("time",$date1)->get(["sum"])->toArray();//获取当前月日入库数量
        $stockoutsum2 = DB::table("stockoutcensus")->where("time",$date2)->get(["sum"])->toArray();//获取前一天月日入库数量
        $stockoutsum3 = DB::table("stockoutcensus")->where("time",$date3)->get(["sum"])->toArray();//获取前两天月日入库数量
        if (empty($stockoutsum1["0"]->sum)){
            $stockoutsum1 = "0";
        }else{
            $stockoutsum1 = $stockoutsum1["0"]->sum;
        }
        if (empty($stockoutsum2["0"]->sum)){
            $stockoutsum2 = "0";
        }else{
            $stockoutsum2 = $stockoutsum2["0"]->sum;
        }
        if (empty($stockoutsum3["0"]->sum)){
            $stockoutsum3 = "0";
        }else{
            $stockoutsum3 = $stockoutsum3["0"]->sum;
        }


        //物资盘点->获取物资名称
        $materials = DB::table("materials")->get(["MaterialName"])->toArray();
        $MaterialName = [];
        foreach ($materials as $k=>$v){
            $MaterialName[] = $v->MaterialName;
        }
//        dd(array_values($MaterialName));
        //物资盘点->获取物资数量
        $material = DB::table("materials")->get(["sum"])->toArray();
        $MaterialSum = [];
        foreach ($material as $k=>$v){
            $MaterialSum[] = $v->sum;
        }
        //物资盘点->获取物资重量
        $materialweight = DB::table("materials")->get(["EachWeight"])->toArray();
        $MaterialEachWeight = [];
        foreach ($materialweight as $k=>$v){
            $MaterialEachWeight[] = $v->EachWeight;
        }
        return view('Index.index')
//            ->with("shortcutButtonsStatistics", $shortcutButtonsStatistics)
//            ->with("deviceDynamicStatus", $deviceDynamicStatus)
//            ->with("fixingAndFixed", $fixingAndFixed)
//            ->with("onlyFixeds", $onlyFixeds)
//            ->with("fixingAndFixedDateList", $fixingAndFixedDateList)
            ->with("date1", $date1)
            ->with("date2", $date2)
            ->with("date3", $date3)
            ->with("stockinsum1", $stockinsum1)
            ->with("stockinsum2", $stockinsum2)
            ->with("stockinsum3", $stockinsum3)
            ->with("stockoutsum1", $stockoutsum1)
            ->with("stockoutsum2", $stockoutsum2)
            ->with("stockoutsum3", $stockoutsum3)
            ->with("MaterialName", json_encode($MaterialName,256))
            ->with("MaterialSum", json_encode($MaterialSum,256))
            ->with("MaterialEachWeight", json_encode($MaterialEachWeight,256));
    }

    public function test()
    {
        $this->onlyOnceFixed();
        dd('FINISH ' . date("H:i:s"));


        foreach (FixWorkflow::with(['EntireInstance'])->where('status', '<>', 'FIXED')->get() as $fixWorkflow) {
            if (rand(0, 1)) {
                $fixWorkflow->fill(['status' => 'FIXED', 'entire_fix_after_count' => 1, 'part_fix_after_count' => 1, 'is_cycle' => true])->saveOrFail();
                $fixWorkflow->EntireInstance->fill(['status' => 'FIXED'])->saveOrFail();
            }

        }
        dd('CYCLE FIX WORKFLOW FINISH ' . date('H:i:s'));


        $partInstances = $this->readPart();
        foreach ($partInstances as $partInstance) {
            if (DB::table('part_instances')->where('identity_code', $partInstance['identity_code'])->first()) continue;
            DB::table('part_instances')->insert($partInstance);
        }
        dd('PART FINISH ' . date('H:i:s'));

        list($entireInstances, $warehouseReports, $warehouseReportEntireInstances) = $this->readEntire();

        foreach ($entireInstances as $entireInstance) {
            if (DB::table('entire_instances')->where('identity_code', $entireInstance['identity_code'])->first()) continue;
            if (DB::table('entire_instances')->where('factory_device_code', $entireInstance['factory_device_code'])->first()) continue;
            DB::table('entire_instances')->insert($entireInstance);
        }

        dd('ENTIRE FINISH ' . date('H:i:s'));
    }

    public function onlyOnceFixed()
    {
        foreach (Fixworkflow::where('status', 'FIXED')->get() as $fixWorkflow) {
            if (rand(0, 100) < 45) {
                $fixWorkflow->fill(['entire_fix_after_count' => 1, 'part_fix_after_count' => 1, 'is_cycle' => 1])->saveOrFail();
            }
        }
    }

    public function readPart()
    {
//        $filePath = storage_path('exports/转辙机部件.xlsx');
//        // 读取excel部件
//        try {
//            $inputFileType = \PHPExcel_IOFactory::identify($filePath);
//            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
//            $objPHPExcel = $objReader->load($filePath);
//        } catch (\Exception $e) {
//            die('加载文件发生错误');
//        }
//
//        // 确定要读取的sheet，什么是sheet，看excel的右下角，真的不懂去百度吧
//        $sheet = $objPHPExcel->getSheet(0);
//        $highestRow = $sheet->getHighestRow(); #行数
//        $highestColumn = $sheet->getHighestColumn(); #列数
//
//        $partInstances = [];  # 整件
//
//        // 获取一行的数据
//        for ($row = 3; $row <= $highestRow; $row++) {
//            // Read a row of data into an array
//            list(
//                $identityCode,  # 唯一编号，所编号
//                $partModelUniqueCode,  # 部件类型
//                $partModelName,  # 部件类型名称
//                $tmp1,  # 周期
//                $entireInstanceIdentityCode,  # 整机编号
//                $tmp2,  # 转辙机型号
//                $tmp3,  # 对应转辙机种类
//                $tmp4,  # 对应转辙机周期
//                $tmp4,  # 供应商
//                $tmp5,  # 厂编号
//                $tmp6,  # 上道状态
//                $tmp7,  # 一级状态
//                $tmp8,  # 仓库名称
//                $tmp9,  # 库存位置
//                $tmp10,  # 去向
//                $tmp11,  # 岔道号
//                $tmp12,  # 牵引
//                $tmp13,  # 来源
//                $tmp14,  # 来源岔号
//                $tmp15,  # 来源牵引
//                $tmp16,  # 出场日期
//                $partInstanceCreatedAt,  # 入所日期
//                $tmp17,
//                $tmp18,
//                $tmp19,
//                $tmp20,
//                $tmp21,
//                $tmp22,
//                $tmp23,
//                $tmp24,
//                $tmp25,
//                $tmp26,
//                $tmp27,
//                $tmp28,
//                $tmp29,
//                $tmp30,
//                $tmp31,
//                $tmp32,
//                $tmp33,
//                $tmp34,
//                $tmp35,
//                $tmp36,
//                $tmp37,
//                $tmp38,
//                $tmp39,
//                $tmp40,
//                $tmp41,
//                $tmp42,
//                $tmp43,
//                $tmp44,
//                $tmp45,
//                $tmp46,
//                $tmp47,
//                $tmp48,
//                $tmp49,
//                $tmp50,
//                $tmp51,
//                $tmp52,
//                $tmp53,
//                $tmp54,
//                $tmp55,
//                $tmp56,
//                $tmp57,
//                $tmp58,
//                $tmp59,
//                $tmp60,
//                ) = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
//            $partInstances[] = [
//                'created_at' => $partInstanceCreatedAt,
//                'updated_at' => $partInstanceCreatedAt,
//                'part_model_unique_code' => $partModelUniqueCode,
//                'part_model_name' => $partModelName,
//                'entire_instance_identity_code' => $entireInstanceIdentityCode,
//                'status' => $tmp6 == '成品' ? 'FIXED' : 'INSTALLED',
//                'factory_name' => null,
//                'factory_device_code' => time() . rand(0, 4) . $row,
//                'identity_code' => $identityCode,
//                'entire_instance_serial_number' => null,
//                'cycle_fix_count' => 0,
//                'un_cycle_fix_count' => 0,
//            ];
//        }
//        return $partInstances;
    }

    public function readEntire()
    {
//        $filePath = storage_path('exports/转辙机整件.xlsx');
//        // 读取excel部件
//        try {
//            $inputFileType = \PHPExcel_IOFactory::identify($filePath);
//            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
//            $objPHPExcel = $objReader->load($filePath);
//        } catch (\Exception $e) {
//            die('加载文件发生错误');
//        }
//
//        // 确定要读取的sheet，什么是sheet，看excel的右下角，真的不懂去百度吧
//        $sheet = $objPHPExcel->getSheet(0);
//        $highestRow = $sheet->getHighestRow(); #行数
//        $highestColumn = $sheet->getHighestColumn(); #列数
//
//        $entireInstances = [];  # 整件
//        $warehouseReports = [];  # 出入所单
//        $warehouseReportEntireInstances = [];  # 出所实例
//
//        for ($rows = 3; $rows <= $highestRow; $rows++) {
//            // Read a row of data into an array
//            list(
//                $serialNumber,  # 设备编号
//                $entireModelIdCode,  # 设备型号
//                $categoryName,  # 种类名称
//                $fixCycleValue,  # 周期
//                $factoryName,  # 供应商
//                $factoryDeviceCode,  # 厂编号
//                $status,  # （上道使用：INSTALLED、成品：FIXED）
//                $inWarehouse,  # （站场：false、室内：true）
//                $warehouseName,  # 仓库名称！ (出所单）
//                $warehouseLocation,  # 库存位置！(出所单）
//                $toDirection,  # 去向！(出所单）
//                $crossroadNumber,  # 岔号！(出所单）
//                $traction,  # 牵引！(出所单）
//                $source,  # 来源！(出所单）
//                $sourceCrossroadNumber,  # 岔号！(出所单）
//                $sourceTraction,  # 来源牵引！(出所单）
//                $madeAt,  # 出场日期！
//                $createdAt,  # 入所日期（入所单）
//                $tmp1,  # 使用日期❎
//                $tmp2,  # 返所日期❎
//                $tmp3,  # 故障检测日期❎
//                $fixBeforeAt,  # 修前检日期（检测单）？
//                $fixAfterAt,  # 修后检日期（检测单）？
//                $fixCheckedAt,  # 段抽验日期（检测单）？
//                $fixWorkshopAt,  # 车间抽验日期（检测单）？
//                $warehouseReportCreatedAt,  # 出所日期（出所单）
//                $forecastInstallAt,  # 理论上道日期！（出所单）
//                $lastInstalledAt,  # 实际安装日期
//                $nextFixingAt,  # 到期日期（需要计算）
//                $scarpingAt,  # 预计报废日期！
//                $tmp4,  # 下道日期
//                $tmp5,  # 返厂日期
//                $tmp6,  # 接收日期
//                $residueUseYear,  # 剩余年限！
//                $lineUniqueCode,  # 线制！（出所单）
//                $openDirection,  # 开向！（出所单）
//                $saidRod,  # 表示杆特征！（出所单）
//                $tmp7,  # 整机故障检测人
//                $fixBeforeProcessorName,  # 修前检测人（检测单）？
//                $fixAfterProcessorName,  # 修后检测人（检测单）？
//                $fixCheckedProcessorName,  # 段抽验人（检测单）？
//                $fixWorkshopProcessorName,  # 车间抽验人（检测单）？
//                $warehouseReportProcessorName,  # 操作人（出所单）
//                $tmp8,  # 交送人！
//                $warehouseReportConnectionName,  # 接收人（出所单）
//                $fixWorkflowNote,  # 入所原因（检修单）？
//                $scarpedNote,  # 报废原因！（出所单）
//                $oldNumber,  # 设备老编号！（出所单）
//                $tmp9,  # 维修费用！（出所单）
//                $oldCheckedCount,  # 验收次数！（出所单）？
//                $railwayName,  # 路局！（出所单）
//                $sectionName,  # 段！（出所单）
//                $baseName,  # 基地名称！（出所单）
//                $tmp10,  # 检修外器材
//                $issueNote,  # 检修缺点！（出所单）？
//                $fixWorkflowProcessNote,  # 检修记事？
//                $tmp11,  # 出所用途
//                $tmp12,  # 配线人
//                $tmp13,  # 其他
//                $tmp14,  # 备注
//                $tmp15,  # 是否最新
//                $tmp16,  # sid
//                ) = $sheet->rangeToArray('A' . $rows . ':' . $highestColumn . $rows, NULL, TRUE, FALSE)[0];
//
//            # 获取对应的类型代码
//            $entireModelIdCodeDB = DB::table('entire_model_id_codes')->where('code', $entireModelIdCode)->first(['category_unique_code', 'entire_model_unique_code', 'code']);
//            # 获取操作人编号
//            $account = Account::where('nickname', $warehouseReportProcessorName)->first(['id']);
//            # 出所单号
//            $warehouseReportSerialNumber = Code::makeSerialNumber('OUT') . $rows;
//
//            $entireInstances[] = [
//                'created_at' => $createdAt,
//                'updated_at' => $createdAt,
//                'entire_model_unique_code' => $entireModelIdCodeDB ? $entireModelIdCodeDB->entire_model_unique_code : '',
//                'entire_model_id_code' => $entireModelIdCodeDB ? $entireModelIdCodeDB->code : $entireModelIdCode,
//                'serial_number' => $serialNumber,
//                'status' => $status == '上道使用' ? 'INSTALLED' : 'FIXED',
//                'maintain_station_name' => $sectionName,
//                'maintain_location_code' => null,
//                'is_main' => null,
//                'factory_name' => $factoryName,
//                'factory_device_code' => $factoryDeviceCode,
//                'identity_code' => $serialNumber,
//                'last_installed_time' => null,
//                'in_warehouse' => $inWarehouse == '室内' ? true : false,
//                'category_name' => $categoryName,
//                'category_unique_code' => $entireModelIdCodeDB ? $entireModelIdCodeDB->category_unique_code : 'S03',
//                'fix_workflow_serial_number' => null,
//                'last_warehouse_report_serial_number_by_out' => null,
//                'is_flush_serial_number' => null,
//                'next_auto_making_fix_workflow_time' => null,
//                'next_fixing_time' => null,
//                'next_auto_making_fix_workflow_at' => null,
//                'next_fixing_month' => null,
//                'next_fixing_day' => null,
//                'fix_cycle_unit' => 'YEAR',
//                'fix_cycle_value' => $fixCycleValue,
//                'cycle_fix_count' => null,
//                'un_cycle_fix_count' => null,
//                'made_at' => $madeAt,
//                'scarping_at' => $scarpingAt,
//                'residue_use_year' => $residueUseYear,
//                'old_number' => $oldNumber,
//            ];

//            $warehouseReports[] = [
//                'created_at' => $warehouseReportCreatedAt ?: null,
//                'updated_at' => $warehouseReportCreatedAt ?: null,
//                'processor_id' => $account ? $account->id : null,
//                'processed_at' => $warehouseReportCreatedAt ?: null,
//                'connection_name' => $warehouseReportConnectionName,
//                'connection_phone' => null,
//                'type' => 'INSTALL',
//                'direction' => 'OUT',
//                'serial_number' => $warehouseReportSerialNumber,
//                'purpose' => null,
//                'warehouse_name' => $warehouseName,
//                'warehouse_location' => $warehouseLocation,
//                'to_direction' => $toDirection,
//                'crossroad_number' => $crossroadNumber,
//                'traction' => $traction,
//                'source' => $source,
//                'source_crossroad_number' => $sourceCrossroadNumber,
//                'source_traction' => $sourceTraction,
//                'forecast_install_at' => $forecastInstallAt?:null,
//                'line_unique_code' => $lineUniqueCode,
//                'open_direction' => $openDirection,
//                'said_rod' => $saidRod,
//                'scarped_note' => $scarpedNote,
//                'railway_name' => $railwayName,
//                'section_name' => $sectionName,
//                'base_name' => $baseName,
//            ];
//
//            $warehouseReportEntireInstances[] = [
//                'warehouse_report_serial_number' => $warehouseReportSerialNumber,
//                'entire_instance_identity_code' => $serialNumber,
////            ];
//        }
//
//        return [$entireInstances, $warehouseReports, $warehouseReportEntireInstances];
    }

    public function entireInstanceFixed($categoryUniqueCode, $entireModelUniqueCode)
    {
//        for ($i = 0; $i < rand(2222, 5555); $i++) {
//            $currentDatetime = date('Y-m-d H:i:s');
//
//            $entireModelIdCodes = ['ZD6-A', 'ZD6-D'];
//
//            $identityCode = \App\Facades\Code::makeEntireInstanceIdentityCode($entireModelUniqueCode);
//
//            $entireInstance = [
//                'created_at' => $currentDatetime,
//                'updated_at' => $currentDatetime,
//                'entire_model_unique_code' => $entireModelUniqueCode,
//                'entire_model_id_code' => $entireModelIdCodes[rand(0, 1)],
//                'status' => 'FIXED',
//                'in_warehouse' => false,
//                'factory_name' => '太原铁路信号设备有限责任公司',
//                'factory_device_code' => $identityCode,
//                'fix_cycle_unit' => 'YEAR',
//                'fix_cycle_value' => 3,
//                'serial_number' => $identityCode,
//                'identity_code' => $identityCode,
//                'category_unique_code' => $categoryUniqueCode,
//            ];
//            DB::table('entire_instances')->insert($entireInstance);
//
//            $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW') . strval($i);
//            $fixWorkflow = [
//                'created_at' => $entireInstance['created_at'],
//                'updated_at' => $entireInstance['created_at'],
//                'entire_instance_identity_code' => $entireInstance['identity_code'],
//                'warehouse_report_serial_number' => null,
//                'status' => 'FIXED',
//                'processor_id' => rand(1, 21),
//                'expired_at' => null,
//                'id_by_failed' => null,
//                'serial_number' => $fixWorkflowSerialNumber,
//                'note' => null,
//                'processed_times' => 0,
//                'stage' => 'FIXED',
//                'is_cycle' => false,
//                'entire_fix_after_count' => 0,
//                'part_fix_after_count' => 0,
//            ];
//            DB::table('fix_workflows')->insert($fixWorkflow);
//
//            if ($categoryUniqueCode == "S03") {
//                $partInstance = [
//                    'created_at' => $currentDatetime,
//                    'updated_at' => $currentDatetime,
//                    'part_model_unique_code' => $entireInstance['entire_model_id_code'],
//                    'entire_instance_identity_code' => $entireInstance['identity_code'],
//                    'status' => 'FIXED',
//                    'factory_name' => '太原铁路信号设备有限责任公司',
//                    'factory_device_code' => time() . $i,
//                    'identity_code' => time() . $i,
//                    'entire_instance_serial_number' => $entireInstance['identity_code'],
//                    'cycle_fix_count' => 0,
//                    'un_cycle_fix_count' => 0,
//                ];
//                DB::table('part_instances')->insert($partInstance);
//            }
//        }
    }

    public function entireInstanceFixing($categoryUniqueCode, $entireModelUniqueCode)
    {
//        for ($i = 0; $i < rand(2222, 5555); $i++) {
//            $currentDatetime = date('Y-m-d H:i:s');
//
//            $entireModelIdCodes = [
//                'ZD6-A',
//                'ZD6-D',
//            ];
//
//            $identityCode = \App\Facades\Code::makeEntireInstanceIdentityCode($entireModelUniqueCode);
//
//            # 不安装
//            $entireInstance = [
//                'created_at' => $currentDatetime,
//                'updated_at' => $currentDatetime,
//                'entire_model_unique_code' => $entireModelUniqueCode,
//                'entire_model_id_code' => $entireModelIdCodes[rand(0, 1)],
//                'status' => 'FIXING',
//                'in_warehouse' => false,
//                'factory_name' => '太原铁路信号设备有限责任公司',
//                'factory_device_code' => $identityCode,
//                'fix_cycle_unit' => 'YEAR',
//                'fix_cycle_value' => 3,
//                'serial_number' => $identityCode,
//                'identity_code' => $identityCode,
//                'category_unique_code' => $categoryUniqueCode,
//            ];
//            DB::table('entire_instances')->insert($entireInstance);
//
//            $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW') . strval($i);
//            $fixWorkflow = [
//                'created_at' => $entireInstance['created_at'],
//                'updated_at' => $entireInstance['created_at'],
//                'entire_instance_identity_code' => $entireInstance['identity_code'],
//                'warehouse_report_serial_number' => null,
//                'status' => 'FIXING',
//                'processor_id' => rand(1, 21),
//                'expired_at' => null,
//                'id_by_failed' => null,
//                'serial_number' => $fixWorkflowSerialNumber,
//                'note' => null,
//                'processed_times' => 0,
//                'stage' => 'PART',
//                'is_cycle' => false,
//                'entire_fix_after_count' => 0,
//                'part_fix_after_count' => 0,
//            ];
//            DB::table('fix_workflows')->insert($fixWorkflow);
//
//            if ($categoryUniqueCode == "S03") {
//                $partInstance = [
//                    'created_at' => $currentDatetime,
//                    'updated_at' => $currentDatetime,
//                    'part_model_unique_code' => $entireInstance['entire_model_id_code'],
//                    'entire_instance_identity_code' => $entireInstance['identity_code'],
//                    'status' => 'FIXED',
//                    'factory_name' => '太原铁路信号设备有限责任公司',
//                    'factory_device_code' => time() . $i,
//                    'identity_code' => time() . $i,
//                    'entire_instance_serial_number' => $entireInstance['identity_code'],
//                    'cycle_fix_count' => 0,
//                    'un_cycle_fix_count' => 0,
//                ];
//                DB::table('part_instances')->insert($partInstance);
//            }
//        }
    }

    public function entireInstanceInstalled($categoryUniqueCode, $entireModelUniqueCode)
    {
//        for ($i = 0; $i < rand(2222, 5555); $i++) {
//            $currentDatetime = date('Y-m-d H:i:s');
//
//            $entireModelIdCodes = [
//                'ZD6-A',
//                'ZD6-D',
//            ];
//
//            $identityCode = \App\Facades\Code::makeEntireInstanceIdentityCode($entireModelUniqueCode);
//
//            # 安装出库
//            # 随机安装时间
//            $randInstalledDay = rand(1, 4);
//            $lastInstalledTime = strtotime("-{$randInstalledDay} month");
//            $nextFixingTime = strtotime("+3 month", $lastInstalledTime);
//            $nextFixingMonth = date('Y-m-01', $nextFixingTime);
//            $nextFixingDay = date('Y-m-d', $nextFixingTime);
//            $nextAutoMakingFixWorkflowTime = strtotime("-2 month", $nextFixingTime);
//            $nextAutoMakingFixWorkflowAt = date('Y-m-01', $nextAutoMakingFixWorkflowTime);
//
//            $entireInstance = [
//                'created_at' => $currentDatetime,
//                'updated_at' => $currentDatetime,
//                'entire_model_unique_code' => $entireModelUniqueCode,
//                'entire_model_id_code' => $categoryUniqueCode == "S03" ? $entireModelIdCodes[rand(0, 1)] : null,
//                'status' => 'INSTALLED',
//                'factory_name' => '太原铁路信号设备有限责任公司',
//                'factory_device_code' => $identityCode,
//                'fix_cycle_unit' => 'YEAR',
//                'fix_cycle_value' => 3,
//                'maintain_station_name' => '十里冲',
//                'maintain_location_code' => rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9),
//                'last_installed_time' => $lastInstalledTime,
//                'serial_number' => $identityCode,
//                'identity_code' => $identityCode,
//                'category_unique_code' => $categoryUniqueCode,
//                'next_auto_making_fix_workflow_time' => $nextAutoMakingFixWorkflowTime,
//                'next_fixing_time' => $nextFixingTime,
//                'next_auto_making_fix_workflow_at' => $nextAutoMakingFixWorkflowAt,
//                'next_fixing_month' => $nextFixingMonth,
//                'next_fixing_day' => $nextFixingDay
//            ];
//            DB::table('entire_instances')->insert($entireInstance);
//
//            $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW') . strval($i);
//            $fixWorkflow = [
//                'created_at' => $entireInstance['created_at'],
//                'updated_at' => $entireInstance['created_at'],
//                'entire_instance_identity_code' => $entireInstance['identity_code'],
//                'warehouse_report_serial_number' => null,
//                'status' => 'FIXED',
//                'processor_id' => rand(1, 21),
//                'expired_at' => null,
//                'id_by_failed' => null,
//                'serial_number' => $fixWorkflowSerialNumber,
//                'note' => null,
//                'processed_times' => 0,
//                'stage' => 'FIXED',
//                'is_cycle' => false,
//                'entire_fix_after_count' => 0,
//                'part_fix_after_count' => 0,
//            ];
//            DB::table('fix_workflows')->insert($fixWorkflow);
//
//            if ($categoryUniqueCode == "S03") {
//                $partInstance = [
//                    'created_at' => $currentDatetime,
//                    'updated_at' => $currentDatetime,
//                    'part_model_unique_code' => $entireInstance['entire_model_id_code'],
//                    'entire_instance_identity_code' => $entireInstance['identity_code'],
//                    'status' => 'FIXED',
//                    'factory_name' => '太原铁路信号设备有限责任公司',
//                    'factory_device_code' => time() . $i,
//                    'identity_code' => time() . $i,
//                    'entire_instance_serial_number' => $entireInstance['identity_code'],
//                    'cycle_fix_count' => 0,
//                    'un_cycle_fix_count' => 0,
//                ];
//                DB::table('part_instances')->insert($partInstance);
//            }
//        }
    }

}
