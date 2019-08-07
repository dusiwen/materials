<?php

namespace App\Http\Controllers;

use App\Facades\Code;
use App\Model\EntireInstance;
use App\Model\EntireModel;
use App\Model\FixWorkflow;
use App\Model\FixWorkflowProcess;
use App\Model\Maintain;
use App\Model\PartInstance;
use App\Model\PartModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class SearchController extends Controller
{
    public function test()
    {
        # 修改整件身份码
        foreach (DB::table('entire_instances')->where('entire_model_unique_code', 'ZD')->get() as $entireInstance) {
            DB::table('entire_instances')->where('id', $entireInstance->id)->update(['identity_code' => Code::makeEntireInstanceIdentityCode('ZD')]);
        }

        # 添加部件
        $i = 0;
        $inserts = [];
        foreach (DB::table('entire_instances')->where('entire_model_unique_code', 'ZD')->get() as $entireInstance) {
            ++$i;
            $entireInstance->identity_code;
            $entireInstance->serial_number;

            $inserts[] = [
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
                'part_model_unique_code' => 'ZD6-A',
                'entire_instance_identity_code' => $entireInstance->identity_code,
                'status' => 'FIXING',
                'factory_name' => '供应商A',
                'factory_device_code' => $i,
                'identity_code' => Code::makePartInstanceIdentityCode('ZD6-A', 'ZD') . $i,
                'entire_instance_serial_number' => $entireInstance->serial_number,
            ];
            ++$i;
            $inserts[] = [
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
                'part_model_unique_code' => 'ZD7-A',
                'entire_instance_identity_code' => $entireInstance->identity_code,
                'status' => 'FIXED',
                'factory_name' => '供应商A',
                'factory_device_code' => $i,
                'identity_code' => Code::makePartInstanceIdentityCode('ZD7-A', 'ZD') . strval($i),
                'entire_instance_serial_number' => $entireInstance->serial_number,
            ];
        }
        DB::table('part_instances')->insert($inserts);
    }

    /**
     * 搜索结果列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        # 1. 判断型号是唯一的还是全部
        # 2. 获取全部型号：EntireInstance->entire_model_unique_code（根据设备类型：EntireInstance->Category）
        # 3. 循环型号且获取该型号下所有的状态和计数 √

        switch (session()->get('searchCondition.search_type')) {
            case 'entire':
                return $this->vagueEntire($request);
                break;
            case 'part':
                return $this->vaguePart($request);
                break;
            case 'fixWorkflow':
                return $this->vagueFixWorkflow($request);
                break;
            default:
                break;
        }
    }

    /**
     * 整件模糊查询
     * @param Request $request
     * @return mixed
     */
    private function vagueEntire(Request $request)
    {
        $entireInstanceModel = EntireInstance::with(['EntireModel'])->orderByDesc('id');
        $maintainStationNames = session()->get('searchCondition.maintain_workshop_name') && !session()->get('searchCondition.maintain_station_name')
            ? Maintain::where('type', 'STATION')
                ->where('parent_unique_code', session()->get('searchCondition.maintain_workshop_name'))
                ->pluck('name')
            : session()->get('searchCondition.maintain_station_name') ?: null;
        $maintainStationNames ? $entireInstanceModel->whereIn('maintain_station_name', $maintainStationNames) : $entireInstanceModel->where('maintain_station_name', null)->whereNotIn('status', ['INSTALLING', 'INSTALLED', 'SCRAP']);
        if (session()->get('searchCondition.maintain_location_code')) $entireInstanceModel->where('maintain_location_code', 'like', '%' . session()->get('searchCondition.maintain_location_code'));
        if (session()->get('searchCondition.unique_code')) $entireInstanceModel->where('category_unique_code', session()->get('searchCondition.unique_code'));
        $entireInstanceCategoryUniqueCodes = array_unique($entireInstanceModel->pluck('category_unique_code')->toArray());  # 获取搜索内容中所有的类型
        $entireInstances = $entireInstanceModel->paginate();

        # 迭代获取所有型号下对应的类型计数
        $entireModels = $request->unique_code
            ? [['unique_code' => session()->get('unique_code')]]
            : EntireModel::whereIn('category_unique_code', $entireInstanceCategoryUniqueCodes)->get(['unique_code', 'name']);
        $statusCounts = [];
        $statusCount = 0;
        $statusKeys = [];

        foreach ($entireModels as $entireModel) {
            $tmp = ['BUY_IN' => 0, 'INSTALLING' => 0, 'INSTALLED' => 0, 'FIXING' => 0, 'FIXED' => 0, 'RETURN_FACTORY' => 0, 'FACTORY_RETURN' => 0, 'SCRAP' => 0];
            $entireInstanceDb = DB::table('entire_instances');
            if (session()->get('searchCondition.maintain_location_code')) $entireInstanceDb->where('maintain_location_code', session()->get('searchCondition.maintain_location_code'));
            if (session()->get('searchCondition.maintain_station_name')) $entireInstanceDb->whereIn('maintain_station_name', $request->session()->get('searchCondition.maintain_station_name'));
            $entireInstanceStatusCount = $entireInstanceDb
                ->select('status')
                ->where('entire_model_unique_code', $entireModel['unique_code'])
                ->orderBy('status')
                ->get();

            foreach ($entireInstanceStatusCount as $item) {
                $statusKeys[] = EntireInstance::$STATUS[$item->status];
                $tmp[$item->status] = $tmp[$item->status] + 1;
                $statusCount += 1;
            }
            $tmp['y'] = $entireModel['unique_code'] . "（{$entireModel['name']}）";
            $statusCounts[] = $tmp;
        }

        return view($this->view())
            ->with('searchType', session()->get('searchCondition.search_type'))
            ->with('statusCounts', json_encode($statusCounts))
            ->with('statusCount', $statusCount)
            ->with('statusCountKeys', $statusKeys)
            ->with('entireInstances', $entireInstances);
    }

    /**
     * 返回页面地址
     * @param null $viewName
     * @return string
     */
    private function view($viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Search.{$viewName}";
    }

    /**
     * 部件模糊查询
     * @param Request $request
     * @return mixed
     */
    private function vaguePart(Request $request)
    {
        # 通过部件型号查询：PartModel
        $searchCondition = session()->get('searchCondition');
        if ($searchCondition['unique_code']) {
            $entireInstances = EntireInstance::where(function ($query) use ($searchCondition) {
                $query->whereIn('identity_code', PartInstance::where('part_model_unique_code', $searchCondition['unique_code'])->pluck('entire_instance_identity_code'));
            })
                ->paginate();
        } else {
            $entireInstances = EntireInstance::paginate();
        }

        # 获取所选部件型号的类型

        # 获取全部型号的计数
        $categoryUniqueCodes = [];
        foreach ($entireInstances as $entireInstance) {
            $categoryUniqueCodes[] = $entireInstance->EntireModel->category_unique_code;
        }
        $categoryUniqueCodes = array_flip(array_flip($categoryUniqueCodes));

        # 迭代获取所有型号下对应的类型计数
        $partModels = $searchCondition['unique_code'] ? [['unique_code' => $searchCondition['unique_code']]] : PartModel::whereIn('category_unique_code', $categoryUniqueCodes)->select('unique_code')->get();
        $statusCounts = [];
        $statusCount = 0;
        $statusKeys = [];

        foreach ($partModels as $partModel) {
            $tmp = ['BUY_IN' => 0, 'INSTALLING' => 0, 'INSTALLED' => 0, 'FIXING' => 0, 'FIXED' => 0, 'RETURN_FACTORY' => 0, 'FACTORY_RETURN' => 0, 'SCRAP' => 0];
            $partInstanceDb = DB::table('part_instances');
            $partInstanceStatusCount = $partInstanceDb
                ->select('status')
                ->where('part_model_unique_code', $partModel['unique_code'])
                ->get();

            foreach ($partInstanceStatusCount as $item) {
                $statusKeys[] = PartInstance::$STATUS[$item->status];
                $tmp[$item->status] = $tmp[$item->status] + 1;
                $statusCount += 1;
            }
            $tmp['y'] = $partModel['unique_code'];
            $statusCounts[] = $tmp;
        }

        return view($this->view())
            ->with('searchType', session()->get('searchCondition.search_type'))
            ->with('statusCounts', json_encode($statusCounts))
            ->with('statusCount', $statusCount)
            ->with('statusCountKeys', $statusKeys)
            ->with('entireInstances', $entireInstances);
    }

    /**
     * 检修单模糊查询
     * @param Request $request
     * @return mixed
     */
    private function vagueFixWorkflow(Request $request)
    {
        # 通过整件型号或检修单状态查询：EntireModel、FixWorkflow
        $searchCondition = session()->get('searchCondition');
        $fixWorkflowModel = FixWorkflow::with(['EntireInstance'])->orderByDesc('id');
        if ($searchCondition['status']) $fixWorkflowModel->where('status', $searchCondition['status']);
        if ($searchCondition['unique_code']) $fixWorkflowModel->whereHas('EntireInstance', function ($entireInstance) use ($searchCondition) {
            $entireInstance->where('entire_model_unique_code', $searchCondition['unique_code']);
        });
        $fixWorkflows = $fixWorkflowModel->paginate();

        # 获取到当前类型下所有的型号
        $categoryUniqueCodes = [];
        foreach ($fixWorkflows as $fixWorkflow) {
            $categoryUniqueCodes[] = $fixWorkflow->EntireInstance->EntireModel->category_unique_code;
        }
        $categoryUniqueCodes = array_flip(array_flip($categoryUniqueCodes));

        # 迭代获取所有型号下对应的类型计数
        $entireModels = $request->unique_code ? [['unique_code' => $request->unique_code]] : EntireModel::whereIn('category_unique_code', $categoryUniqueCodes)->select('unique_code')->get();
        $statusCounts = [];
        $statusCount = 0;
        $statusKeys = [];
        foreach ($entireModels as $entireModel) {
            $tmp = ['BUY_IN' => 0, 'INSTALLING' => 0, 'INSTALLED' => 0, 'FIXING' => 0, 'FIXED' => 0, 'RETURN_FACTORY' => 0, 'FACTORY_RETURN' => 0, 'SCRAP' => 0];
            $entireInstanceDb = DB::table('entire_instances');
            $entireInstanceStatusCount = $entireInstanceDb
                ->select('status')
                ->where('entire_model_unique_code', $entireModel['unique_code'])
                ->orderBy('status')
                ->get();

            foreach ($entireInstanceStatusCount as $item) {
                $statusKeys[] = EntireInstance::$STATUS[$item->status];
                $tmp[$item->status] = $tmp[$item->status] + 1;
                $statusCount += 1;
            }
            $tmp['y'] = $entireModel['unique_code'];
            $statusCounts[] = $tmp;
        }

        return view($this->view())
            ->with('searchType', session()->get('searchCondition.search_type'))
            ->with('statusCounts', json_encode($statusCounts))
            ->with('statusCount', $statusCount)
            ->with('statusCountKeys', $statusKeys)
            ->with('fixWorkflows', $fixWorkflows);

    }

    /**
     * 搜索详情
     * @param $entireInstanceIdentityCode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($entireInstanceIdentityCode)
    {
        try {
            $entireInstance = EntireInstance::with([
                'EntireModel',
                'EntireModel.Category',
                'EntireModel.Measurements',
                'EntireModel.Measurements.PartModel',
                'PartInstances',
                'PartInstances.PartModel',
                'FixWorkflows' => function ($fixWorkflow) {
                    $fixWorkflow->orderByDesc('id');
                },
                'FixWorkflow.WarehouseReport',
                'FixWorkflow.Processor',
                'FixWorkflow.FixWorkflowProcesses',
                'FixWorkflow.FixWorkflowProcesses.Measurement',
                'FixWorkflow.FixWorkflowProcesses.Processor',
                'FixWorkflow.FixWorkflowProcesses.Measurement.PartModel',
                'FixWorkflow.EntireInstance.PartInstances',
                'FixWorkflow.EntireInstance.PartInstances.PartModel',
            ])
                ->where('identity_code', $entireInstanceIdentityCode)
                ->firstOrFail();

            # 获取最后一次检测记录（右侧显示）
            $lastFixWorkflowRecodeEntire = FixWorkflowProcess::with([
                'FixWorkflowRecords',
                'FixWorkflowRecords.Measurement',
                'FixWorkflowRecords.EntireInstance',
                'FixWorkflowRecords.EntireInstance.EntireModel',
            ])
                ->orderByDesc('id')
                ->where('type', 'ENTIRE')
                ->where('fix_workflow_serial_number', $entireInstance->fix_workflow_serial_number)
                ->first();
            $lastFixWorkflowRecodePart = FixWorkflowProcess::with([
                'FixWorkflowRecords',
                'FixWorkflowRecords.Measurement',
                'FixWorkflowRecords.PartInstance',
                'FixWorkflowRecords.PartInstance.PartModel',
            ])
                ->orderByDesc('id')
                ->where('type', 'PART')
                ->where('fix_workflow_serial_number', $entireInstance->fix_workflow_serial_number)
                ->first();

            return view($this->view())
                ->with('fixWorkflows', $entireInstance->FixWorkflows)
                ->with('entireInstance', $entireInstance)
                ->with('fixWorkflow', $entireInstance->FixWorkflow)
                ->with('lastFixWorkflowRecodeEntire', $lastFixWorkflowRecodeEntire)
                ->with('lastFixWorkflowRecodePart', $lastFixWorkflowRecodePart);
        } catch (ModelNotFoundException $exception) {
//            return back()->withInput()->with('danger', '数据不存在');
            return back()->withInput()->with('danger', $exception->getMessage());
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return back()->with('danger', $exceptionMessage . ':' . $exceptionFile . ':' . $exceptionLine);
        }
    }

    /**
     * 保存搜索条件
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            session()->put('searchCondition', $request->all());
            $identityCode = null;

            $methodName = strval($request->search_type);
            return $this->$methodName($request, $identityCode);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getFile() . ':' . $exception->getLine(), 500);
        }
    }

    /**
     * 整件搜索
     * @param Request $request
     * @param string|null $identityCode
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    private function entire(Request $request, string $identityCode = null)
    {
        list($type, $key, $value) = $request->get('factory_device_code') . $request->get('serial_number')
            ? $request->get('serial_number')
                ? ['serial_number', 'serial_number', $request->get('serial_number')]
                : ['factory_device_code', 'factory_device_code', $request->get('factory_device_code')]
            : [null, null, null];

        switch ($type) {
            case 'serial_number':
            case 'factory_device_code':
                # 精确查询
                $identityCode = EntireInstance::select('identity_code')
                    ->where($key, $value)
                    ->firstOrFail()
                    ->identity_code;
                break;
        }
        return url('search', $identityCode);
    }

    /**
     * 部件搜索
     * @param Request $request
     * @param string|null $identityCode
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    private function part(Request $request, string $identityCode = null)
    {
        # 精确查询
        # 通过部件的条件查询整件
        list($type, $key, $value) = $request->get('factory_device_code') . $request->get('serial_number')
            ? $request->get('serial_number')
                ? ['serial_number', 'serial_number', $request->get('serial_number')]
                : ['factory_device_code', 'factory_device_code', $request->get('factory_device_code')]
            : [null, null, null];

        switch ($type) {
            case 'serial_number':
            case 'factory_device_code':
                # 精确查询
                $identityCode = EntireInstance::select('identity_code')
                    ->whereHas(
                        'PartInstances',
                        function ($q) use ($key, $value) {
                            $q->where($key, $value);
                        })
                    ->firstOrFail()
                    ->identity_code;
                break;
        }
        return url('search', $identityCode);
    }

    /**
     * 检修单搜索
     * @param Request $request
     * @param string|null $identityCode
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    private function fixWorkflow(Request $request, string $identityCode = null)
    {
        list($type, $key, $value) = $request->get('factory_device_code') . $request->get('serial_number')
            ? $request->get('serial_number')
                ? ['serial_number', 'serial_number', $request->get('serial_number')]
                : ['factory_device_code', 'factory_device_code', $request->get('factory_device_code')]
            : [null, null, null];

        switch ($type) {
            case 'serial_number':
            case 'factory_device_code':
                # 精确查询
                # 整件的所编号或者厂编号进行查询
                $identityCode = EntireInstance::select('identity_code')
                    ->where($key, $value)
                    ->firstOrFail()
                    ->identity_code;
                break;
        }
        return url('search', $identityCode);
    }
}
