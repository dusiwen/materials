<?php

namespace App\Http\Controllers\Measurement;

use App\Http\Controllers\Controller;
use App\Model\FixWorkflowProcess;
use App\Model\FixWorkflowRecord;
use App\Model\Measurement;
use App\Model\PartModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Jericho\BuilderFormRequest;

class FixWorkflowRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|BuilderFormRequest|BuilderFormRequest[]|object|null
     */
    public function index()
    {
        if (\request()->ajax()) {
            switch (\request()->get('operationMode')) {
                case 'bindingFixWorkflowProcess':
                    session()->put('bindingFixWorkflowProcess', ['partModel' => \request()->get('partModelUniqueCode')]);
                    $fixWorkflowRecordBuilder = FixWorkflowRecord::with([
                        'Processor',
                        'Measurement',
                        'PartInstance',
                        'PartInstance.PartModel',
                        'PartInstance.EntireInstance.Category',
                    ]);
                    $fixWorkflowRecordBuilder->where('fix_workflow_process_serial_number', null);
                    $fixWorkflowRecordBuilder->where('type', \request()->get('type'));
                    $fixWorkflowRecordBuilder->orderByDesc('id');
                    if (\request()->get('partModelUniqueCode')) $fixWorkflowRecordBuilder->whereHas('PartInstance.PartModel', function ($partModel) {
                        $partModel->where('unique_code', \request()->get('partModelUniqueCode'));
                    });
                    return $fixWorkflowRecordBuilder->get();
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * 测试数据绑定到测试单页面
     * @param $fixWorkflowProcessSerialNumber
     * @return mixed
     */
    public function getBindingFixWorkflowProcess($fixWorkflowProcessSerialNumber)
    {
        $partModels = \request()->get('categoryUniqueCode') ? PartModel::where('category_unique_code', \request()->get('categoryUniqueCode'))->get() : PartModel::all();
        $fixWorkflowRecordBuilder = FixWorkflowRecord::with([
            'Processor',
            'Measurement',
            'PartInstance',
            'PartInstance.PartModel',
            'PartInstance.EntireInstance.Category',
        ]);
        $fixWorkflowRecordBuilder->where('fix_workflow_process_serial_number', null);
        $fixWorkflowRecordBuilder->where('type', \request()->get('type'));
        $fixWorkflowRecordBuilder->orderByDesc('id');
        if (\request()->get('partModelUniqueCode')) $fixWorkflowRecordBuilder->whereHas('PartInstance.PartModel', function ($partModel) {
            $partModel->where('unique_code', \request()->get('partModelUniqueCode'));
        });

        return view($this->view('bindingFixWorkflowProcess'))
            ->with('partModels', $partModels)
            ->with('fixWorkflowRecords', $fixWorkflowRecordBuilder->paginate())
            ->with('fixWorkflowProcessSerialNumber', $fixWorkflowProcessSerialNumber);
    }

    private function view($viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Measurement.FixWorkflowRecord.{$viewName}";
    }

    /**
     * 绑定测试数据到测试测试单
     * @param Request $request
     * @param $fixWorkflowProcessSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function postBindingFixWorkflowProcess(Request $request, $fixWorkflowProcessSerialNumber)
    {
        try {
            $fixWorkflowRecord = FixWorkflowRecord::where('serial_number', $request->get('fixWorkflowRecordSerialNumber'))->first();
            if (!$fixWorkflowRecord) throw new \Exception('测试数据不存在');
            $currentRecord = FixWorkflowRecord::where('fix_workflow_process_serial_number', $fixWorkflowProcessSerialNumber)->where('measurement_identity_code', $fixWorkflowRecord->measurement_identity_code)->first();
            if (!$currentRecord) throw new \Exception("替换数据不存在：{$fixWorkflowProcessSerialNumber}：{$fixWorkflowRecord->measurement_identity_code}");
            $currentRecord->fill([
                'measured_value' => $fixWorkflowRecord->measured_value,
                'is_allow' => $fixWorkflowRecord->is_allow,
            ])
                ->saveOrFail();
            $fixWorkflowRecord->fill(['fix_workflow_process_serial_number' => $fixWorkflowProcessSerialNumber])->saveOrFail();
            $fixWorkflowRecord->delete();

            return Response::make('绑定成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 解除检测单与检测数据关系页面
     * @param $fixWorkflowProcessSerialNumber
     * @return mixed
     */
    public function getBoundFixWorkflowProcess($fixWorkflowProcessSerialNumber)
    {
        $fixWorkflowRecordBuilder = FixWorkflowRecord::onlyTrashed()
            ->with([
                'Processor',
                'Measurement',
                'PartInstance',
                'PartInstance.PartModel',
                'PartInstance.EntireInstance.Category',
            ]);
        $fixWorkflowRecordBuilder->where('fix_workflow_process_serial_number', $fixWorkflowProcessSerialNumber);
        $fixWorkflowRecordBuilder->where('type', \request()->get('type'));
        $fixWorkflowRecordBuilder->orderByDesc('id');

        return view($this->view('boundFixWorkflowProcess'))
            ->with('fixWorkflowRecords', $fixWorkflowRecordBuilder->paginate())
            ->with('fixWorkflowProcessSerialNumber', $fixWorkflowProcessSerialNumber);
    }

    /**
     * 解除测试单和测试数据关系
     * @param Request $request
     * @param string $fixWorkflowProcessSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function postCancelBoundFixWorkflowProcess(Request $request, string $fixWorkflowProcessSerialNumber)
    {
        try {
            $fixWorkflowRecord = FixWorkflowRecord::onlyTrashed()->where('serial_number', $request->get('fixWorkflowRecordSerialNumber'))->first();
            if (!$fixWorkflowRecord) throw new \Exception('替换数据不存在');
            $currentRecord = FixWorkflowRecord::where('fix_workflow_process_serial_number', $fixWorkflowProcessSerialNumber)->where('measurement_identity_code', $fixWorkflowRecord->measurement_identity_code)->first();
            if (!$currentRecord) throw new \Exception("测试数据不存在：{$fixWorkflowProcessSerialNumber}：{$fixWorkflowRecord->measurement_identity_code}");
            $currentRecord->fill([
                'measured_value' => null,
                'is_allow' => false,
            ])
                ->saveOrFail();
            $fixWorkflowRecord->fill(['fix_workflow_process_serial_number' => null])->saveOrFail();
            $fixWorkflowRecord->restore();  # 恢复测试数据

            return Response::make('解绑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->view());
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
     * @param {string} $fixWorkflowProcessPartSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $fixWorkflowProcessPartSerialNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $fixWorkflowRecordSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy($fixWorkflowRecordSerialNumber)
    {
        try {
            $fixWorkflowRecord = FixWorkflowRecord::where('serial_number', $fixWorkflowRecordSerialNumber)->firstOrFail();
            $fixWorkflowRecord->delete();
            if (!$fixWorkflowRecord->trashed()) return Response::make('删除失败', 500);

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
     * 保存测试数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function saveMeasuredValue(Request $request)
    {
        try {
            $fixWorkflowProcess = null;
            DB::transaction(function () use ($request, &$fixWorkflowProcess) {
                # 获取测试模板数据
                $measurement = Measurement::where('identity_code', $request->measurementIdentityCode)->firstOrFail();

                # 校验实测值是否符合标准值
                $isAllow = false;
                if ($measurement->allow_min == null && $measurement->allow_max == null) {
                    throw new \Exception(0);
                } else {
                    if ($measurement->allow_min == $measurement->allow_max) {
                        $isAllow = floatval($request->get('measuredValue')) == floatval($measurement->allow_min);
                    } elseif ($measurement->allow_min == null && $measurement->allow_max != null) {
                        $isAllow = floatval($request->get('measuredValue')) <= floatval($measurement->allow_max);
                    } elseif ($measurement->allow_min != null && $measurement->allow_max == null) {
                        $isAllow = floatval($request->get('measuredValue')) >= floatval($measurement->allow_min);
//                        throw new \Exception(json_encode([intval($isAllow), floatval($request->get('measuredValue')), floatval($measurement->allow_min)]));
                    } else {
                        $isAllow = floatval($request->get('measuredValue')) >= floatval($measurement->allow_min) && floatval($request->get('measuredValue')) <= floatval($measurement->allow_max);
                    }
                }

                # 记录修改值
                $fixWorkflowRecord = FixWorkflowRecord::where('serial_number', $request->get('serialNumber'))->firstOrfail();
                $fixWorkflowRecord->measured_value = $request->get('measuredValue');
                $fixWorkflowRecord->is_allow = $isAllow;
                $fixWorkflowRecord->saveOrFail();

                # 获取该记录单下所有记录
                $fixWorkflowProcess = FixWorkflowProcess::with(['FixWorkflowRecords'])
                    ->whereHas('FixWorkflowRecords', function ($query) use ($request) {
                        $query->where('serial_number', $request->get('serialNumber'));
                    })->firstOrFail();

                # 修改该次检测记录是否合格
                $fixWorkflowProcessIsAllow = true;
                foreach ($fixWorkflowProcess->FixWorkflowRecords as $fixWorkflowRecord) {
                    if (!$fixWorkflowRecord->is_allow) {
                        $fixWorkflowProcessIsAllow = false;
                        break;
                    }
                }
                $fixWorkflowProcess->fill(['is_allow' => $fixWorkflowProcessIsAllow])->saveOrFail();
            });
            return Response::json($fixWorkflowProcess->FixWorkflowRecords);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 保存实测模糊描述窗口
     */
    public function getSaveMeasuredExplain()
    {
        $measurement = Measurement::where('identity_code', \request()->get('measurementIdentityCode'))->firstOrFail();
        $fixWorkflowRecord = FixWorkflowRecord::where('serial_number', \request()->get('fixWorkflowRecordSerialNumber'))->firstOrFail();

        return view($this->view('saveMeasuredExplain_ajax'))
            ->with('measurement', $measurement)
            ->with('fixWorkflowRecord', $fixWorkflowRecord)
            ->with('fixWorkflowRecordSerialNumber', \request()->get('fixWorkflowRecordSerialNumber'));
    }

    /**
     * 保存实测模糊描述窗口
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function postSaveMeasuredExplain(Request $request)
    {
        try {
            $fixWorkflowRecord = FixWorkflowRecord::where('serial_number', $request->serial_number)->firstOrFail();
            $fixWorkflowRecord->fill($request->all())->saveOrFail();

            # 修改该次检测单是否通过
            $fixWorkflowRecord->FixWorkflowProcess->fill(['is_allow' => $request->is_allow])->saveOrFail();

            return Response::make('保存成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 记录测试人
     * @param Request $request
     * @param string $fixWorkflowRecordSerialNumber
     * @return array|\Illuminate\Http\Response
     */
    public function postSaveProcessor(Request $request, string $fixWorkflowRecordSerialNumber)
    {
        try {
            $fixWorkflowRecord = FixWorkflowRecord::where('serial_number', $fixWorkflowRecordSerialNumber)->firstOrFail();
            $fixWorkflowRecord->fill(['processor_id' => $request->get('processorId', null)])->saveOrFail();

            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 记录测试时间
     * @param Request $request
     * @param string $fixWorkflowRecordSerialNumber
     * @return \Illuminate\Http\Response
     */
    public function postSaveProcessedAt(Request $request, string $fixWorkflowRecordSerialNumber)
    {
        try {
            $fixWorkflowRecord = FixWorkflowRecord::where('serial_number', $fixWorkflowRecordSerialNumber)->firstOrFail();
            $fixWorkflowRecord->processed_at = $request->get('processedAt', null);
            $fixWorkflowRecord->saveOrFail();

            return Response::make('编辑成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }
}
