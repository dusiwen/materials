<?php

namespace App\Http\Controllers\Measurement;

use App\Facades\Code;
use App\Http\Controllers\Controller;
use App\Model\FixWorkflow;
use App\Model\FixWorkflowProcess;
use App\Model\FixWorkflowRecord;
use App\Model\PartInstance;
use App\Model\PivotEntireModelAndPartModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class FixWorkflowProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fixWorkflowProcesses = FixWorkflowProcess::where('type', \request()->get('type'))
            ->where('fix_workflow_serial_number', \request()->get('fixWorkflowSerialNumber'))
            ->orderByDesc('id')
            ->paginate();

        return view($this->view())
            ->with('fixWorkflowProcesses', $fixWorkflowProcesses);
    }

    private function view($viewName = null): string
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Measurement.FixWorkflowProcess.{$viewName}";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->view('create_ajax'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $newFixWorkflowProcessSerialNumber = null;
            DB::transaction(function () use ($request, &$newFixWorkflowProcessSerialNumber) {
                # 获取检测单和检测单总数
                $fixWorkflowProcessCount = FixWorkflowProcess::where('fix_workflow_serial_number', $request->get('fix_workflow_serial_number'))
                        ->where('type', $request->get('type'))
                        ->where('stage', $request->get('stage'))
                        ->count('id') + 1;

                # 新建检测单
                $fixWorkflowProcess = new FixWorkflowProcess;
                $newFixWorkflowProcessSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW_PROCESS');
                $fixWorkflowProcess->fill(
                    array_merge(
                        $request->all(), [
                            'serial_number' => $newFixWorkflowProcessSerialNumber,
                            'auto_explain' => "第{$fixWorkflowProcessCount}次：" . FixWorkflowProcess::$STAGE[$request->get('stage')],
                            'numerical_order' => $fixWorkflowProcessCount,
                        ]
                    )
                )
                    ->saveOrFail();

                # 保存检修单检测次数
                $fixWorkflow = FixWorkflow::where('serial_number', $request->get('fix_workflow_serial_number'))->firstOrFail();
                $fixWorkflow->fill(['processed_times' => $fixWorkflowProcessCount])->saveOrFail();

                # 创建空测试数据
                $fixWorkflow = FixWorkflow::with([
                    'EntireInstance',
                    'EntireInstance.Measurements' => function ($q) {
                        $q->where('part_model_unique_code', null);
                    },
                    'EntireInstance.PartInstances',
                    'EntireInstance.PartInstances.PartModel',
                    'EntireInstance.PartInstances.PartModel.Measurements',
                ])
                    ->where('serial_number', $request->get('fix_workflow_serial_number'))
                    ->firstOrFail();

                $i = 0;
                $fixWorkflowRecords = [];
                switch ($request->type) {
                    case 'ENTIRE':
                        foreach ($fixWorkflow->EntireInstance->Measurements as $measurement) {
                            $i++;
                            $fixWorkflowRecords[] = [
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'fix_workflow_process_serial_number' => $newFixWorkflowProcessSerialNumber,
                                'entire_instance_identity_code' => $fixWorkflow->entire_instance_identity_code,
                                'measurement_identity_code' => $measurement->identity_code,
                                'serial_number' => Code::makeSerialNumber('FIX_WORKFLOW_PROCESS_ENTIRE') . "_{$i}",
                                'type' => $request->type,
                            ];
                        }
                        break;
                    case 'PART':
                        foreach ($fixWorkflow->EntireInstance->PartInstances as $partInstance) {
                            foreach ($partInstance->PartModel->Measurements as $measurement) {
                                $i++;
                                $fixWorkflowRecords[] = [
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'fix_workflow_process_serial_number' => $newFixWorkflowProcessSerialNumber,
                                    'part_instance_identity_code' => $partInstance->identity_code,
                                    'measurement_identity_code' => $measurement->identity_code,
                                    'serial_number' => Code::makeSerialNumber('FIX_WORKFLOW_PROCESS_PART') . "_{$i}",
                                    'type' => $request->type,
                                ];
                            }
                        }
                        break;
                }
                if (!$fixWorkflowRecords) throw new \Exception('测试模板为空');
//                if (!$fixWorkflowRecords) throw new \Exception(json_encode($fixWorkflow->EntireInstance->PartInstances,256));
                if (!DB::table('fix_workflow_records')->insert($fixWorkflowRecords)) throw new \Exception('创建检测空记录失败');

                # 更新检修单阶段
                $fixWorkflow->fill(['stage' => $request->get('stage')])->saveOrFail();

                # 修改整件状态
                $fixWorkflow->EntireInstance->fill(['status' => 'FIXING', 'in_warehouse' => false])->saveOrFail();

                # 修改部件状态
                DB::table('part_instances')->where('entire_instance_identity_code', $fixWorkflow->EntireInstance)->update(['status' => 'FIXING']);
            });

            return Response::make($newFixWorkflowProcessSerialNumber);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
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
     * @param string $serialNumber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(string $serialNumber)
    {
        try {
            $fixWorkflowProcess = FixWorkflowProcess::with([
                'FixWorkflow',
                'FixWorkflow.EntireInstance',
                'FixWorkflow.EntireInstance.PartInstances',
                'FixWorkflow.EntireInstance.PartInstances.PartModel',
                'FixWorkflow.EntireInstance.PartInstances.PartModel.Measurements',
                'FixWorkflowRecords' => function ($fixWorkflowRecord) {
                    $fixWorkflowRecord->orderBy('measurement_identity_code');
                },
                'FixWorkflowRecords.EntireInstance',
                'FixWorkflowRecords.EntireInstance.EntireModel',
                'FixWorkflowRecords.PartInstance',
                'FixWorkflowRecords.PartInstance.PartModel',
                'FixWorkflowRecords.Measurement',
                'FixWorkflowRecords.Processor'
            ])
                ->where('serial_number', $serialNumber)
                ->firstOrFail();

            return view($this->view())
                ->with('fixWorkflowSerialNumber', \request()->get('fixWorkflowSerialNumber'))
                ->with('type', \request()->get('type'))
                ->with('page', \request()->get('page'))
                ->with('fixWorkflowProcess', $fixWorkflowProcess);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $fixWorkflowProcessSerialNumber
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function update(Request $request, string $fixWorkflowProcessSerialNumber)
    {
        try {
            # 检查当前测试结果是否存在不合格
            $fixWorkflowProcessCount = count(FixWorkflowProcess::with(['FixWorkflowRecords'])->where('serial_number', $fixWorkflowProcessSerialNumber)->firstOrFail()->FixWorkflowRecords);  # 该测试单应该具有的测试数据总数
            $fixWorkflowRecordIsAllowCount = FixWorkflowRecord::where('fix_workflow_process_serial_number', $fixWorkflowProcessSerialNumber)->where('is_allow', 1)->count('id');
            $fixWorkflowProcessIsAllow = ($fixWorkflowProcessCount == $fixWorkflowRecordIsAllowCount);

            # 保存备注信息
            $fixWorkflowProcess = FixWorkflowProcess::where('serial_number', $fixWorkflowProcessSerialNumber)->firstOrFail();
            $fixWorkflowProcess->fill(array_merge($request->all(), ['is_allow' => $fixWorkflowProcessIsAllow]))->saveOrFail();

            $fixWorkflow = FixWorkflow::with(["EntireInstance"])->where("serial_number", $fixWorkflowProcess->fix_workflow_serial_number)->firstOrFail();
            # 检查是否有部件
            $hasPartModel = PivotEntireModelAndPartModel::where('entire_model_unique_code', $fixWorkflow->EntireInstance->entire_model_unique_code)->count('part_model_unique_code') > 0;# 获取最后一次检测单
            $lastFixWorkflowProcessEntire = FixWorkflowProcess::with(['FixWorkflow'])
                ->orderByDesc('id')
                ->where('type', 'ENTIRE')
                ->where('stage', 'FIX_AFTER')
                ->where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                ->where("is_allow", true)
                ->first(["id"]);
            $lastFixWorkflowProcessPart = FixWorkflowProcess::with(['FixWorkflow'])
                ->orderByDesc('id')
                ->where('type', 'PART')
                ->where('stage', "FIX_AFTER")
                ->where('fix_workflow_serial_number', $fixWorkflow->serial_number)
                ->where("is_allow", true)
                ->first(["id"]);
            if ($hasPartModel) {
                # 如果有部件
                if ($lastFixWorkflowProcessEntire && $lastFixWorkflowProcessPart) {
                    $fixWorkflow->fill(['stage' => 'WAIT_CHECK'])->saveOrFail();
                }
            } else {
                # 没有部件
                if ($lastFixWorkflowProcessEntire) {
                    $fixWorkflow->fill(['stage' => 'WAIT_CHECK'])->saveOrFail();
                }
            }
            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make($exceptionMessage, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $fixWorkflowProcessSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy($fixWorkflowProcessSerialNumber)
    {
        try {
            $fixWorkflowProcess = FixWorkflowProcess::where('serial_number', $fixWorkflowProcessSerialNumber)->firstOrFail();
            $fixWorkflowProcess->delete();
            if (!$fixWorkflowProcess->trashed()) return Response::make('删除失败', 500);

            return Response::make('删除成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make($exceptionMessage, 500);
        }
    }

    /**
     * 部件检测窗口（用来选择需要检测的部件）
     * @return \Illuminate\Http\Response
     */
    public function getPart()
    {
        try {
            $partInstances = PartInstance::where(
                'entire_instance_identity_code',
                \request()->get('entireInstanceIdentityCode')
            )
                ->paginate();

            $fixWorkflow = FixWorkflow::where('serial_number', \request()->get('fixWorkflowIdentityCode'))->fisrtOrFail();

            return view($this->view())
                ->with('partInstances', $partInstances)
                ->with('fixWorkflow', $fixWorkflow);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('danger', '意外错误');
        }
    }

    public function postFixWorkflowProcessPart()
    {

    }
}
