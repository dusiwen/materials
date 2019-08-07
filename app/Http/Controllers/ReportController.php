<?php

namespace App\Http\Controllers;

use App\Model\EntireInstance;
use App\Model\EntireModel;
use App\Model\Factory;
use App\Model\FixWorkflow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function hy()
    {
        $inputFileName = storage_path('app/hy.xls');
        // 读取excel文件
        try {
            $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (\Exception $e) {
            dd($e);
        }

        foreach ($objPHPExcel->getSheetNames() as $sheetName) {
            $sheet = $objPHPExcel->getSheetByName($sheetName);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $insertData = [];
            // 获取一行的数据
            for ($row = 2; $row <= $highestRow; $row++) {
                list($rowId,
                    $smStationName,
                    $locationCode,
                    $entireModelUniqueCode,
                    $maintainCode,
                    $makedAt,
                    $fixedAt,
                    $nextFixingAt,
                    $fixProcessorName,
                    $checkProcessorName)
                    = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

                if ($smStationName == null &&
                    $locationCode == null &&
                    $entireModelUniqueCode == null &&
                    $maintainCode == null &&
                    $makedAt == null &&
                    $fixedAt == null &&
                    $nextFixingAt == null &&
                    $fixProcessorName == null &&
                    $checkProcessorName == null)
                    break;

                $insertData[] = [
                    'fix_workshop_name' => '衡阳',
                    'station_name' => $sheetName,
                    'sm_station_name' => $smStationName,
                    'location_code' => $locationCode,
                    'entire_model_unique_code' => $entireModelUniqueCode,
                    'maintain_code' => $maintainCode,
                    'maked_at' => $makedAt,
                    'fixed_at' => $fixedAt,
                    'next_fixing_at' => $nextFixingAt,
                    'fix_processor_name' => $fixProcessorName,
                    'check_processor_name' => $checkProcessorName,
                ];
            }
            if (count($insertData) > 0) DB::table('hengyang')->insert($insertData);
            dump("成功导入：{$sheetName}");
        }
        dd('导入完成');
    }

    /**
     * 现场车间
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function workshop()
    {
        # 型号总计数
        $entireModelUniqueCodes = [];
        foreach (DB::table('hengyang')->select('entire_model_unique_code')->where('entire_model_unique_code', '<>', null)->groupBy('entire_model_unique_code')->get() as &$entireModelUniqueCode) {
            $entireModelUniqueCodes[$entireModelUniqueCode->entire_model_unique_code] = DB::table('hengyang')->where('entire_model_unique_code', $entireModelUniqueCode->entire_model_unique_code)->count('id');
        }

        $stationNames = DB::table('hengyang')->select('station_name')->where('station_name', '<>', null)->groupBy('station_name')->get();

        return view($this->view())
            ->with('entireModelUniqueCodes', $entireModelUniqueCodes)
            ->with('stationNames', $stationNames);
    }

    private function view($viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Report.{$viewName}";
    }

    /**
     * 车站页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function station(string $stationName)
    {
        # 型号总计数
        $entireModelUniqueCodes = [];
        foreach (DB::table('hengyang')->select('entire_model_unique_code')
                     ->where('entire_model_unique_code', '<>', null)
                     ->groupBy('entire_model_unique_code')
                     ->where('station_name', $stationName)
                     ->get()
                 as &$entireModelUniqueCode) {
            $entireModelUniqueCodes[$entireModelUniqueCode->entire_model_unique_code] = DB::table('hengyang')->where('entire_model_unique_code', $entireModelUniqueCode->entire_model_unique_code)->count('id');
        }
        return view($this->view())
            ->with('stationName', $stationName)
            ->with('entireModelUniqueCodes', $entireModelUniqueCodes);
    }

    /**
     * 统计单次维修合格的
     */
    public function onlyOnceFixed()
    {
        # 计算获取数据类型时间起点和终点
        $type = request()->get('type', 0) > 12 ? 12 : request()->get('type', 0);
        if ($type) {
            # 获取若干月数据
            $time = [Carbon::now()->subMonth(request()->get('type', 0))->toDateString(), Carbon::parse("+1day -1second")->toDateString()];
        } else {
            # 获取当月数据
            $time = [date('Y-m-01'), Carbon::parse("+1day -1second")->toDateString()];
        }

        $fixWorkflows = FixWorkflow::with([
            'EntireInstance',
            'EntireInstance.EntireModel',
            'EntireInstance.Category',
        ])
            ->whereBetween('created_at', $time)
            ->where('entire_fix_after_count', 1)
            ->where('part_fix_after_count', 1)
            ->where('is_cycle', true)
            ->paginate();

        return view($this->view())
            ->with('fixWorkflows', $fixWorkflows);
    }

    public function quality()
    {
        $EntireModel = EntireModel::with("EntireInstances", "EntireInstances.FixWorkflow", "Category");
        if (request()->get('category_unique_code')) $EntireModel->where('category_unique_code', request()->get('category_unique_code'));
        if (request()->get('entire_model_unique_code')) $EntireModel->where('unique_code', request()->get('entire_model_unique_code'));
        $entireModels = $EntireModel->get();
        $fixWorkflowCounts = [];

        # 获取所有厂家
        $Factory = Factory::with("EntireInstances");
        if (request()->get("factory_name")) $Factory->where("name", request()->get("factory_name"));
        $factories = $Factory->get();

        foreach ($entireModels as $entireModel) {
            foreach ($factories as $factory) {
                $EntireInstance = EntireInstance::withCount([
                    'FixWorkflow' => function ($fixWorkflow) {
                        $fixWorkflow->where('is_cycle', false);
                        if (request()->get('date')) {
                            $fixWorkflow->whereBetween('updated_at', explode("~", request()->get("date", date("Y-m-d") . "~" . date("Y-m-d"))));
                        }
                    }])
                    ->with(['FixWorkflow']);

                $EntireInstance
                    ->where('factory_name', $factory->name)
                    ->where('entire_model_unique_code', $entireModel->unique_code);
                $totalEntireInstanceCount = $EntireInstance->count();
                $EntireInstance->has('FixWorkflow', '>', 2);
                $hasManyFixWorkflowEntireInstanceCount = $EntireInstance->count();
                if (($hasManyFixWorkflowEntireInstanceCount + $totalEntireInstanceCount) != 0) {
                    $rate = round($hasManyFixWorkflowEntireInstanceCount / $totalEntireInstanceCount, 2) * 100;
                } else {
                    $rate = 0;
                }

                $fixWorkflowCounts[$entireModel->unique_code . ':' . $factory->id] = [
                    "entire_model_name" => $entireModel->name,
                    "entire_model_unique_code" => $entireModel->unique_code,
                    "category_name" => $entireModel->Category->name,
                    "factory_name" => $factory->name,
                    "total_count" => $totalEntireInstanceCount,
                    "many_fix_count" => $hasManyFixWorkflowEntireInstanceCount,
                    "rate" => $rate
                ];
            }
        }

        return view($this->view())
            ->with("fixWorkflowCounts", $fixWorkflowCounts);
    }

    public function qualityItem($entireModelUniqueCode)
    {
        $EntireInstance = EntireInstance::with(['FixWorkflow', 'FixWorkflow.Processor', 'Category', 'EntireModel'])
            ->withCount([
                'FixWorkflow' => function ($fixWorkflow) {
                    $fixWorkflow->where('is_cycle', false);
                    if (request()->get('date')) $fixWorkflow->whereBetween('updated_at', explode('~', request()->get('date', date('Y-m-d') . '~' . date('Y-m-d'))));
                }
            ]);

        $EntireInstance->where('entire_model_unique_code', $entireModelUniqueCode);
        if (request()->get('factory_name')) $EntireInstance->where('factory_name', request()->get('factory_name'));
        $EntireInstance->has('FixWorkflow', '>', 2);
        $entireInstances = $EntireInstance->paginate();

        return view($this->view())
            ->with('entireInstances', $entireInstances);
    }

    public function qualityShow($entireInstanceIdentityCode)
    {
        $FixWorkflow = FixWorkflow::with(['FixWorkflowProcesses', 'EntireInstance', 'FixWorkflowProcesses.Processor']);
        $fixWorkflows = $FixWorkflow
            ->where('is_cycle', false)
            ->where('entire_instance_identity_code', $entireInstanceIdentityCode)
            ->paginate();
        return view($this->view())
            ->with('fixWorkflows', $fixWorkflows);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
