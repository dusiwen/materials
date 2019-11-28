<?php

namespace App\Http\Controllers\Measurement;

use App\Facades\Code;
use App\Facades\WarehouseReport;
use App\Http\Controllers\Controller;
use App\Model\Account;
use App\Model\EntireInstance;
use App\Model\EntireInstanceChangePartLog;
use App\Model\EntireInstanceLog;
use App\Model\FixWorkflow;
use App\Model\FixWorkflowProcess;
use App\Model\PartInstance;
use App\Model\PartModel;
use App\Model\PivotEntireModelAndPartModel;
use App\Model\UnCycleFixReport;
use App\Model\WarehouseProductInstance;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use function Hprose\Future\all;

class FixWorkflowController extends Controller
{
    /**
     * 盘点列表页面
     * 获取传感器重量
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        dump($request->all());
        $date1 = date("Y-m-d", time());//获取当前月日

        //1.获取传感器重量放到相应的盘点表中
        $tray = DB::table("tray")->where("MaterialCode", "!=", NULL)->get()->toArray();
        foreach ($tray as $k => $v) {
            $MaterialCode = $v->MaterialCode;  //获取物资编码
            $MaterialName = $v->MaterialName;  //获取物资名称
            $weights = $v->weights;  //获取传感器传回的重量值
            if ($weights > 10000) {
                $weights = "0";
            }
            $EachWeight = DB::table("materials")->where("MaterialCode", $MaterialCode)->get()->toArray();  //根据物资编码获取物资的每个重量
            $weight = number_format($weights / $EachWeight[0]->EachWeight);  //四舍五入取整(后期要改)
//            DB::table("wm")->where("MaterialsDescribe", $MaterialName)->update(["WMNumber" => $weight]);
            DB::table("tray")->where("MaterialCode", $MaterialCode)->update(["weights" => $weights]);
        }
        //2.获取wm盘点表数据
        $wm = DB::table("wm");
        if ($request->get("date1")) {
            if (strpos($request->get("date1"), "~")) {
                //从wm盘点页饼图进来
            } else {
                //从首页饼图进来(查询当天的wm表)
                $date2 = [$request->get("date1"),$request->get("date1")];
                $wm->whereBetween("WMDates", $date2);
            }
        }
        if ($request->get("amp;name")){
            if ($request->get("amp;name") == "账物不一致"){
//                dump("账物不一致");
//                $wm->whereRaw('Number != WMNumber');
                $wm->where("WMStatus","账物不一致");
            }elseif ($request->get("amp;name") == "超期未出库"){
//                dump("超期未出库");
                $wm->where("WMStatus","超期未出库");
            }elseif ($request->get("amp;name") =="盘点正常"){
//                dump("盘点正常");
//                $wm->whereRaw('Number = WMNumber');
                $wm->where("WMStatus","盘点正常");
            }
        }
        $wm = $wm->orderBy("id", "desc")->get()->toArray();


        //首页差异动态分析
        $count = DB::table("wm")->count();  //获取总条数
        //账物不一致数量
        $byz = DB::table("wm")->where("WMStatus","账物不一致")->count();
        //盘点正常数量
        $zc = DB::table("wm")->where("WMStatus","盘点正常")->count();
        //超期未出库
        $cq = DB::table("wm")->where("WMStatus","超期未出库")->count();

        return view($this->view())
            ->with('date1', $date1)
            ->with("byz", $byz)
            ->with("zc", $zc)
            ->with("cq", $cq)
            ->with('wm', $wm);
    }

    private function view($viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Measurement.FixWorkflow.{$viewName}";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function create()
    {
        try {
            $fixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW');
            DB::transaction(function () use ($fixWorkflowSerialNumber) {
                # 检查是否是验收员
                if (session()->get('account.supervision') == 0) throw new \Exception("该设备存在未完成的检修单");

                # 验证该整件下是否存在未完成的检修单
                $unFixedCount = FixWorkflow::with(['EntireInstance'])->where('entire_instance_identity_code', \request()->get('identity_code'))
                    ->whereNotIn('status', ['FIXED'])
                    ->count('id');
                if ($unFixedCount) throw new \Exception("该设备存在未完成的检修单");

                # 插入检修单
                $fixWorkflow = new FixWorkflow;
                $fixWorkflow->fill([
                    'entire_instance_identity_code' => \request()->get('identity_code'),
                    'status' => 'FIXING',
                    'processor_id' => session()->get('processor_id'),
                    'serial_number' => $fixWorkflowSerialNumber,
                    'stage' => 'UNFIX',
                    'type' => \request()->get('type'),
                    'check_serial_number' => \request()->get('type', 'FIX') == 'CHECK' ? FixWorkflow::where('entire_instance_identity_code', \request()->get('identity_code'))->where('type', 'FIX')->where('status', 'FIXED')->firstOrFail(['serial_number'])->serial_number : null,
                ])
                    ->saveOrFail();

                # 修改整件实例中检修单序列号、状态和在库状态
                $fixWorkflow->EntireInstance->fill([
                    'fix_workflow_serial_number' => $fixWorkflowSerialNumber,
                    'status' => 'FIXING',
                    'in_warehouse' => false
                ])
                    ->saveOrFail();

                # 修改实例中部件的状态
                DB::table('part_instances')
                    ->where('entire_instance_identity_code', \request()->get('identity_code'))
                    ->update(['status' => 'FIXING']);

                # 添加整件非正常检修记录
                $fixUnCycleReport = new UnCycleFixReport;
                $fixUnCycleReport->fill([
                    'entire_instance_identity_code' => $fixWorkflow->entire_instance_identity_code,
                    'fix_workflow_serial_number' => $fixWorkflow->serial_number,
                ]);
            });

            return redirect(url('measurement/fixWorkflow', $fixWorkflowSerialNumber) . '/edit');
        } catch (ModelNotFoundException $exception) {
            return back()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->with('danger', $exception->getMessage());
        }
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
     * @param string $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function show($serialNumber)
    {
        # 读取该检修单历史
        $fixWorkflows = FixWorkflow::with([
            'EntireInstance',
            'EntireInstance.EntireModel',
            'EntireInstance.EntireModel.Category',
            'EntireInstance.EntireModel.Measurements',
            'EntireInstance.EntireModel.Measurements.PartModel',
            'WarehouseReport',
            'Processor',
            'FixWorkflowProcesses',
            'FixWorkflowProcesses.Measurement',
            'FixWorkflowProcesses.Processor',
            'FixWorkflowProcesses.Measurement.PartModel'
        ])
            ->where('serial_number', $serialNumber)
            ->orderByDesc('id')
            ->get();

        return view($this->view())
            ->with('fixWorkflows', $fixWorkflows);
    }

    /**
     * 账务不一致分析页面
     * Show the form for editing the specified resource.
     *
     * @param $serialNumber
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function edit($serialNumber)
    {
        try {
            $id = $_GET['id'];
            $differ = DB::table("wm")->where("id", $id)->get()->toArray();  //获取对应id盘点差异分析表
            $fixWorkflow = FixWorkflow::with([
                'EntireInstance',
                'EntireInstance.EntireModel',
                'EntireInstance.EntireModel.PartModels',
                'EntireInstance.EntireModel.Category',
                'EntireInstance.EntireModel.Measurements',
                'EntireInstance.EntireModel.Measurements.PartModel',
                'EntireInstance.PartInstances',
                'EntireInstance.PartInstances.PartModel',
                'WarehouseReport',
                'Processor',
                'FixWorkflowProcesses',
                'FixWorkflowProcesses.Measurement',
                'FixWorkflowProcesses.Processor',
                'FixWorkflowProcesses.Measurement.PartModel',
            ])
                ->where('serial_number', $serialNumber)
                ->orderByDesc('id')
                ->firstOrFail();

            # 获取检修单下整件检测记录（左侧显示）
            $fixWorkflowProcesses_entire = FixWorkflowProcess::where('type', 'ENTIRE')
                ->where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                ->orderByDesc('updated_at')
                ->get();
            $fixWorkflowProcesses_part = FixWorkflowProcess::where('type', 'PART')
                ->where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                ->orderByDesc('updated_at')
                ->get();

            # 获取最后一次检测记录（右侧显示）
            $lastFixWorkflowRecodeEntire = FixWorkflowProcess::with([
                'FixWorkflowRecords',
                'FixWorkflowRecords.Measurement',
                'FixWorkflowRecords.EntireInstance',
                'FixWorkflowRecords.EntireInstance.EntireModel',
            ])
                ->orderByDesc('id')
                ->where('type', 'ENTIRE')
                ->where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                ->first();
            $lastFixWorkflowRecodePart = FixWorkflowProcess::with([
                'FixWorkflowRecords',
                'FixWorkflowRecords.Measurement',
                'FixWorkflowRecords.PartInstance',
                'FixWorkflowRecords.PartInstance.PartModel',
            ])
                ->orderByDesc('id')
                ->where('type', 'PART')
                ->where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                ->first();

            # 检查是否有部件
            $hasPartModel = PivotEntireModelAndPartModel::where('entire_model_unique_code', $fixWorkflow->EntireInstance->entire_model_unique_code)->count('part_model_unique_code') > 0;# 获取最后一次检测单
            # 根据检修单阶段获取最后一次检测记录结果
            $isAllow = false;
            $fixWorkflowStage = $fixWorkflow->flipStage($fixWorkflow->stage);
            if ($fixWorkflowStage == 'CHECKED' || $fixWorkflowStage == 'WORKSHOP' || $fixWorkflowStage == 'SECTION') {
                $lastFixWorkflowProcessEntireIsAllow = FixWorkflowProcess::with(['FixWorkflow'])
                    ->orderByDesc('id')
                    ->where('type', 'ENTIRE')
                    ->where('stage', $fixWorkflow->prototype('stage'))
                    ->where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                    ->first();
                $lastFixWorkflowProcessPartIsAllow = FixWorkflowProcess::with(['FixWorkflow'])
                    ->orderByDesc('id')
                    ->where('type', 'PART')
                    ->where('stage', $fixWorkflow->prototype('stage'))
                    ->where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                    ->first();

                if ($hasPartModel) {
                    # 如果存在部件
                    if ($lastFixWorkflowProcessEntireIsAllow != null && $lastFixWorkflowProcessPartIsAllow != null) {
                        if ($lastFixWorkflowProcessEntireIsAllow->is_allow == 1 && $lastFixWorkflowProcessPartIsAllow->is_allow == 1) $isAllow = true;
                    }
                } else {
                    # 如果不存在部件
                    if ($lastFixWorkflowProcessEntireIsAllow != null && $lastFixWorkflowProcessEntireIsAllow->is_allow == 1) $isAllow = true;
                }
            }

            if ($isAllow) {  # 检修完成
                # 修改检修单状态
                if ($fixWorkflow->prototype('status') != 'RETURN_FACTORY')
                    $fixWorkflow->fill(['status' => 'FIXED'])->saveOrFail();

                # 修改整件在库状态
                if (!in_array(array_flip(EntireInstance::$STATUS)[$fixWorkflow->EntireInstance->status], ['INSTALLED', 'INSTALLING', 'RETURN_FACTORY']))
                    $fixWorkflow->EntireInstance->fill(['status' => 'FIXED', 'in_warehouse' => true])->saveOrFail();

                # 如果是周期修统计该订单下有多少次“修后检”
                if ($fixWorkflow->is_cycle) {
                    if ($hasPartModel) {
                        # 有部件
                        # 统计该订单下有多少修后检（整件）
                        $fixWorkflow->fill([
                            'entire_fix_after_count' => FixWorkflowProcess::where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                                ->where('type', 'ENTIRE')
                                ->where('stage', 'FIX_AFTER')
                                ->count('id')
                        ])
                            ->saveOrFail();
                        # 统计该订单下有多少修后检（整件）
                        $fixWorkflow->fill([
                            'part_fix_after_count' => FixWorkflowProcess::where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                                ->where('type', 'PART')
                                ->where('stage', 'FIX_AFTER')
                                ->count('id')
                        ])
                            ->saveOrFail();
                    } else {
                        # 没有部件
                        # 统计该订单下有多少修后检
                        $fixWorkflow->fill([
                            'entire_fix_after_count' => FixWorkflowProcess::where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                                ->where('type', 'ENTIRE')
                                ->where('stage', 'FIX_AFTER')
                                ->count('id')
                        ])
                            ->saveOrFail();
                    }
                }
            } else {  # 检修不通过
                if ($fixWorkflow->prototype('status') != 'RETURN_FACTORY') {
                    # 修改检修单状态
                    $fixWorkflow->fill(['status' => 'FIXING'])->saveOrFail();
                    # 修改整件在库状态
                    $fixWorkflow->EntireInstance->fill(['status' => 'FIXING', 'in_warehouse' => false])->saveOrFail();
                }
            }

            # 获取该整件下所有部件计数
            $partModels = PivotEntireModelAndPartModel::where('entire_model_unique_code', $fixWorkflow->EntireInstance->EntireModel->unique_code)->pluck('part_model_unique_code');
            $partModelCount = [];
            foreach ($partModels as $partModel) {
                $partModelCount[$partModel] = DB::table('part_instances')->where('entire_instance_identity_code', null)->where('part_model_unique_code', $partModel)->count('id');
            }

            return view('Measurement.FixWorkflow.edit')
                ->with('fixWorkflow', $fixWorkflow)
                ->with('fixWorkflowProcesses_entire', $fixWorkflowProcesses_entire)
                ->with('fixWorkflowProcesses_part', $fixWorkflowProcesses_part)
                ->with('partModels', $partModels)
                ->with('partModelCount', $partModelCount)
                ->with('lastFixWorkflowRecodeEntire', $lastFixWorkflowRecodeEntire)
                ->with('differ', $differ)
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
            return back()->with('danger', '意外错误' . $exceptionMessage);
        }
    }

    /**
     * 保存盘点差异分析报告
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
//            $fixWorkflow = FixWorkflow::where('serial_number', $serialNumber)->firstOrFail();
//            $fixWorkflow->fill($request->all())->saveOrFail();
            DB::table("wm")->where("id", $id)->update(["Analyse" => $request->input("note")]);
            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $fixWorkflowSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy($fixWorkflowSerialNumber)
    {
        try {
            # 查看是否有上一张检修单
            $fixWorkflow = FixWorkflow::with(['EntireInstance'])->where('serial_number', $fixWorkflowSerialNumber)->firstOrFail();
            $fixWorkflowCount = FixWorkflow::where('entire_instance_identity_code', $fixWorkflow->entire_instance_identity_code)->where('id', $fixWorkflow->id)->count('id');
            if ($fixWorkflowCount > 1) {
                $fixWorkflow->EntireInstance->fill(['status' => 'FIXED', 'in_warehouse' => true])->saveOrFail();
            } else {
                $fixWorkflow->EntireInstance->fill(['status' => 'FIXING', 'in_warehouse' => false])->saveOrFail();
            }

            $fixWorkflow->delete();
            if (!$fixWorkflow->trashed()) return Response::make('删除失败', 500);

            return Response::make('删除成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 标记检修单：已完成
     * @param string $fixWorkflowSerialNumber 检修单编号
     * @return \Illuminate\Http\Response
     */
    public function fixed(string $fixWorkflowSerialNumber)
    {
        try {
            DB::transaction(function () use ($fixWorkflowSerialNumber) {
                # 修改检修单状态
                $fixWorkflow = FixWorkflow::where('serial_number', $fixWorkflowSerialNumber)->firstOrFail();
                $fixWorkflow->fill(['status' => 'FIXED'])->saveOrFail();

                # 修改设备实例状态
                $entireInstance = EntireInstance::where('fix_workflow_serial_number', $fixWorkflowSerialNumber)->firstOrFail();
                $entireInstance->fill([
                    'status' => 'FIXED',
                    'in_warehouse' => true
                ])
                    ->saveOrFail();

                # 修改部件实例状态
                DB::table('part_instances')
                    ->where('entire_instance_identity_code', $entireInstance->identity_code)
                    ->update(['status' => 'FIXED']);
            });

            return Response::make('检修单已完成');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 标记订单抽检失败
     * @param int $fixWorkflowId 检修单编号
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function spotCheckFailed($fixWorkflowId)
    {
        try {
            $fixWorkflow = FixWorkflow::findOrFail($fixWorkflowId);
            if ($fixWorkflow->flipStatus($fixWorkflow->status) != 'WORKSHOP') return Response::make('检修单状态错误：' . $fixWorkflow->status . '（' . $fixWorkflow->flipStatus($fixWorkflow->status) . '）', 403);
            $fixWorkflow->fill(['status' => 'SPOT_CHECK_FAILED'])->saveOrFail();

            # 添加检修单
            $newFixWorkflow = new FixWorkflow;
            $newFixWorkflow->fill([
                'warehouse_product_instance_open_code' => $fixWorkflow->warehouse_product_instance_open_code,
                'warehouse_report_product_id' => $fixWorkflow->warehouse_report_product_id,
                'status' => 'UNFIX',
                'id_by_failed' => $fixWorkflowId
            ])->saveOrFail();

            # 修改设备实例外键
            $warehouseProductInstance = WarehouseProductInstance::where('fix_workflow_id', $fixWorkflowId)->firstOrFail();
            $warehouseProductInstance->fill(['fix_workflow_id' => $newFixWorkflow->id])->saveOrFail();

            return Response::make('标记成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 获取下一阶段检修单地址
     * @param int $fixWorkflowId 检修单编号
     * @return \Illuminate\Http\Response
     */
    public function nextFixWorkflow($fixWorkflowId)
    {
        try {
            $fixWorkflow = FixWorkflow::where('id_by_failed', $fixWorkflowId)->firstOrFail();
            return Response::make(url('measurement/fixWorkflow') . '/' . $fixWorkflow->id . '/edit');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 获取上一阶段检修单地址
     * @param int $fixWorkflowId 检修单编号
     * @return \Illuminate\Http\Response
     */
    public function previousFixWorkflow($fixWorkflowId)
    {
        try {
            $fixWorkflow = FixWorkflow::where('id', $fixWorkflowId)->firstOrFail();
            return Response::make(url('measurement/fixWorkflow') . '/' . $fixWorkflow->id . '/edit');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 记录更换部件页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getChangePartInstance()
    {
        try {
            # 获取检修单数据
            $fixWorkflow = FixWorkflow::with([
                'EntireInstance',
                'EntireInstance.EntireModel',
                'EntireInstance.EntireModel.PartModels',
            ])->where('serial_number', \request()->get('fix_workflow_serial_number'))
                ->firstOrFail();

            # 获取当前整件型号下所有部件型号
            $partModels = PartModel::whereIn(
                'unique_code',
                PivotEntireModelAndPartModel::where(
                    'entire_model_unique_code', $fixWorkflow->EntireInstance->entire_model_unique_code
                )->pluck('part_model_unique_code')
            )->get();

            return view($this->view('changePartInstance_ajax'))
                ->with('fixWorkflow', $fixWorkflow)
                ->with('partModels', $partModels);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * 记录更换部件
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function postChangePartInstance(Request $request)
    {
//        return $request->all();
        try {
            DB::transaction(function () use ($request) {
                # 修改部件所属
                $partInstance = PartInstance::where('identity_code', $request->get('part_instance_identity_code'))->firstOrFail();
                $partInstance->fill(['entire_instance_identity_code' => $request->get('entire_instance_identity_code')])->saveOrFail();

                # 记录日志
                $entireInstanceLog = new EntireInstanceChangePartLog;
                $entireInstanceLog->fill([
                    'entire_instance_identity_code' => $request->get('entire_instance_identity_code'),
                    'part_instance_identity_code' => $request->get('part_instance_identity_code'),
                    'fix_workflow_serial_number' => $request->get('fix_workflow_serial_number'),
                ])->saveOrFail();
            });

            return Response::make('保存成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 卸载部件
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postUninstallPartInstance()
    {
        try {
            DB::transaction(function () {
                # 卸载部件
                $partInstance = PartInstance::where('identity_code', \request()->get('partInstanceIdentityCode'))->firstOrFail();
                $entireInstanceIdentityCode = $partInstance->entire_instance_identity_code;
                $partInstance->fill(['entire_instance_identity_code' => null])->saveOrFail();

                # 记录整件操作日志
                $entireInstanceLog = new EntireInstanceLog;
                $entireInstanceLog->fill([
                    'name' => '卸载部件',
                    'description' => '部件：' . \request()->get('partInstanceIdentityCode'),
                    'entire_instance_identity_code' => $entireInstanceIdentityCode
                ])->saveOrFail();
            });

            return Response::make('卸载成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * 报废部件
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postScrapPartInstance(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                # 获取部件信息
                $partInstance = PartInstance::with([
                    'EntireInstance',
                    'PartModel'
                ])
                    ->where('identity_code', $request->get('partInstanceIdentityCode'))
                    ->firstOrFail();
                # 修改部件状态
                $partInstance->fill(['entire_instance_identity_code' => null, 'status' => 'SCRAP'])->saveOrFail();

                # 记录整件更换部件日志
                $entireInstanceChangePartLog = new EntireInstanceChangePartLog;
                $entireInstanceChangePartLog->fill([
                    'entire_instance_identity_code' => $request->get('entireInstanceIdentityCode'),
                    'part_instance_identity_code' => $request->get('partInstanceIdentityCode'),
                    'fix_workflow_serial_number' => $request->get('fixWorkflowSerialNumber'),
                    'note' => "部件报废：{$partInstance->PartModel->name}：{$partInstance->PartModel->unique_code}（{$partInstance->factory_device_code}）",
                ])
                    ->saveOrFail();

                # 记录整件操作日志
                $entireInstanceLog = new EntireInstanceLog;
                $entireInstanceLog->fill([
                    'name' => '报废部件',
                    'description' => "部件报废：{$partInstance->PartModel->name}：{$partInstance->PartModel->unique_code}（{$partInstance->factory_device_code}）",
                    'entire_instance_identity_code' => $request->get('entireInstanceIdentityCode'),
                ])
                    ->saveOrFail();
            });

            return Response::make('报废成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * 出库安装页面
     */
    public function getInstall()
    {
        try {
            $fixWorkflow = FixWorkflow::where('serial_number', \request()->get('fixWorkflowSerialNumber'))->firstOrFail();
            $accounts = Account::orderByDesc('id')->pluck('nickname', 'id');

            return view($this->view('install_ajax'))
                ->with('accounts', $accounts)
                ->with('fixWorkflow', $fixWorkflow);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 设备安装出库
     * @param Request $request
     * @param string $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function postInstall(Request $request, $serialNumber)
    {
        try {
            WarehouseReport::fixWorkflowOutOnce($request, FixWorkflow::with(['EntireInstance', 'EntireInstance.EntireModel'])->where('serial_number', $serialNumber)->firstOrFail());
            return Response::make('安装成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 检修单：入所页面
     * @param $fixWorkflowSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function getIn($fixWorkflowSerialNumber)
    {
        return view($this->view('in_ajax'))
            ->with('fixWorkflowSerialNumber', $fixWorkflowSerialNumber)
            ->with('accounts', Account::orderByDesc('id')->pluck('nickname', 'id'));
    }

    /**
     * 检修单：入所
     * @param Request $request
     * @param string $fixWorkflowSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function postIn(Request $request, string $fixWorkflowSerialNumber)
    {
        try {
            # 获取检修单数据
            WarehouseReport::fixWorkflowInOnce($request, FixWorkflow::with(['EntireInstance'])->where('serial_number', $fixWorkflowSerialNumber)->firstOrFail());
            return Response::make('入所成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 返厂维修页面
     * @param string $fixWorkflowSerialNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReturnFactory(string $fixWorkflowSerialNumber)
    {
        return view($this->view('returnFactory_ajax'))
            ->with('fixWorkflowSerialNumber', $fixWorkflowSerialNumber)
            ->with('accounts', Account::orderByDesc('id')->pluck('nickname', 'id'));
    }

    /**
     * 返厂维修
     * @param Request $request
     * @param string $fixWorkflowSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function postReturnFactory(Request $request, string $fixWorkflowSerialNumber)
    {
        try {
            # 获取检修单数据
            WarehouseReport::returnFactoryOutOnce($request, FixWorkflow::with(['EntireInstance'])->where('serial_number', $fixWorkflowSerialNumber)->firstOrFail());
            return Response::make('出所成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * 返厂入所页面
     * @param string $fixWorkflowSerialNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFactoryReturn(string $fixWorkflowSerialNumber)
    {
        return view($this->view('factoryReturn_ajax'))
            ->with('fixWorkflowSerialNumber', $fixWorkflowSerialNumber)
            ->with('accounts', Account::orderByDesc('id')->pluck('nickname', 'id'));
    }

    /**
     * 返厂入所
     * @param Request $request
     * @param string $fixWorkflowSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function postFactoryReturn(Request $request, string $fixWorkflowSerialNumber)
    {
        try {
            # 获取检修单数据
            WarehouseReport::factoryReturnInOnce($request, FixWorkflow::with(['EntireInstance'])->where('serial_number', $fixWorkflowSerialNumber)->firstOrFail());
            return Response::make('入所成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }
}
