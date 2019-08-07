<?php

namespace App\Http\Controllers\Warehouse;

use App\Facades\Code;
use App\Http\Controllers\Controller;
use App\Model\EntireInstance;
use App\Model\FixWorkflow;
use App\Model\WarehouseBatchReport;
use App\Model\WarehouseReport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * 物资入库界面
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

//        $str = file_get_contents('../workerman/stdoutFile.txt');
//
//        //用换行的分割符（\r\n）把字符串分割为数组，也就是把每一行分割为成数组的一个值
//
//        $array = explode("\r\n",$str);
//        for ($i=0;$i<count($array);$i++){
//            $url=$array[$i];
//        }
//        //可以根据自己需要，循环输出从开始行到结束行的内容
//        //示例：输出文本中第4行内容（因为数组的键值是从0开始的，所以第4行也就是键值3）
//        $weight = hexdec(substr($array[$i-4],54,4)); //16进制转为10进制获取重量
//            dd($weight);
        $warehouseReportModel = WarehouseReport::with(['Processor', 'WarehouseReportEntireInstances', 'WarehouseReportEntireInstances.EntireInstance'])->orderByDesc('updated_at');
        if (\request()->get('direction')) $warehouseReportModel->where('direction', \request()->get('direction'));
        if (\request()->get('category_unique_code')) $warehouseReportModel->whereHas('WarehouseReportEntireInstances.EntireInstance', function ($entireInstance) {
            $entireInstance->where('category_unique_code', \request()->get('category_unique_code'));
        });
        if (\request()->get('type')) $warehouseReportModel->where('type', \request()->get('type'));
        if (\request()->get('updated_at')) $warehouseReportModel->whereBetween('updated_at', explode('~', \request()->get('updated_at')));
        $warehouseReports = $warehouseReportModel->paginate();



        //入库单列表页(将相同time的分在同一数组中)
        $stockin = DB::table("stockin")->orderBy("id","desc")->get()->toArray();
        $stockinByTime = [];
        foreach ($stockin as $k=>$v){
            key_exists($v->time, $stockinByTime) ? array_push($stockinByTime[$v->time], $v) : $stockinByTime[$v->time] = [$v];

        }
        return view($this->view())
            ->with('warehouseReports', $warehouseReports)
            ->with('stockin', $stockin)
            ->with('stockinByTime', $stockinByTime);
    }

    protected function view($viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Warehouse.Report.{$viewName}";
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
     * @param string $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function show($serialNumber)
    {
        try {
            $warehouseReport = WarehouseReport::with([
                'Processor',
                'WarehouseReportEntireInstances',
                'WarehouseReportEntireInstances.EntireInstance',
                'WarehouseReportEntireInstances.EntireInstance.EntireModel',
            ])
                ->where('serial_number', $serialNumber)
                ->firstOrFail();

            switch (\request()->get('type')) {
                case 'print':
                    $view = view($this->view('print'));
                    break;
                default:
                    $view = view($this->view());
                    break;
            }

            return $view->with('warehouseReport', $warehouseReport);
        } catch (ModelNotFoundException $exception) {
            return back()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->with('danger', $exception->getMessage());
        }
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
     * 入库单入库操作
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
//            $str = file_get_contents('../workerman/stdoutFile.txt');
//
//            //用换行的分割符（\r\n）把字符串分割为数组，也就是把每一行分割成数组的一个值
//
//            $array = explode("\r\n",$str);
//            for ($i=0;$i<count($array);$i++){
//                $url=$array[$i];
//            }
//            //可以根据自己需要，循环输出从开始行到结束行的内容
//            //示例：输出文本中第4行内容（因为数组的键值是从0开始的，所以第4行也就是键值3）
//            $weight = hexdec(substr($array[$i-4],54,4)); //16进制转为10进制获取重量
//            dd($weight);
            $date = DB::table("stockin")->where("id",$id)->get(); //获取入库单信息

            $StockIn_EachWeight = $date[0]->StockIn_EachWeight;  //获取入库单物资单个重量
//            $WMNumber = 5.7/$StockIn_EachWeight;  //根据入库单单个重量算出盘点数量
            $WMNumbers = 24;
            $mt_rand = mt_rand(360000000,369999999);//生成9位盘点凭证号(最多9位)
            $data = time();//入库单入库时间戳(盘点时间)
            DB::table("wm")->insert(["Company"=>$date[0]->StockIn_Units,"WMcode"=>$mt_rand,"MaterialsCode"=>$date[0]->StockIn_MaterialCode,"MaterialsDescribe"=>$date[0]->StockIn_MaterialName,"StorageType"=>"G01","Positions"=>"G01-010101","Unit"=>$date[0]->StockIn_Unit,"WarehouseNumber"=>"EBA","Number"=>$date[0]->StockIn_Number,"Location"=>"YEBA","WMDate"=>$data,"WMNumber"=>$WMNumbers]);//入库单生成盘点表
            DB::table("stockin")->where("id",$id)->update(["StockIn_Status"=>"已入库",]);
//            $entireInstance = EntireInstance::where('identity_code', $identityCode)->firstOrFail();
//            $entireInstance->fill(['status' => 'SCRAP'])->saveOrFail();
            //入库成功后将入库统计表stockincensus数量+1
            $time = date("Y-m-d",time());
            $stockincensus = DB::table("stockincensus")->orderBy('id', 'desc')->first();
            if (!empty($stockincensus)){
                if ($stockincensus->time == $time){
                    //存在当前入库日期将入库数量+1
                    $sum = DB::table("stockincensus")->where("time",$time)->select("sum")->get()->toArray();
                    $sums =$sum[0]->sum;
                    DB::table("stockincensus")->where("time",$time)->update(["sum"=>$sums+1]);
                }else{
                    //不存在则创建点当前日期并将数量设置未1
                    DB::table("stockincensus")->insert(["time"=>$time,"sum"=>"1"]);
                }
            }else{
                DB::table("stockincensus")->insert(["time"=>$time,"sum"=>"1"]);
            }

//            return Response::make($stockincensus->time,404);
//            return Response::make($aaa[0]->time,404);
//            $i = 0;
//            foreach ($stockincensus as $k=>$v){
//                if ($stockincensus[$i]->time == $time){
//                    $sum = DB::table("stockincensus")->where("time",$time)->select("sum")->get()->toArray();
//                    $sums =$sum[0]->sum;
//                    DB::table("stockincensus")->where("time",$time)->update(["sum"=>$sums+1]);
//                    $i++;
//                }else{
//                    DB::table("stockincensus")->insert(["time"=>$time,"sum"=>"1"]);
//                    return Response::make('入库成功');
//                }
//
//            }
//            if (DB::table("stockincensus"))

            return Response::make('入库成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 入库单冲销
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::table("stockin")->where("id",$id)->delete();
//            $entireInstance = EntireInstance::where('identity_code', $identityCode)->firstOrFail();
//            $entireInstance->fill(['status' => 'SCRAP'])->saveOrFail();

            return Response::make('删除成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    public function getScanInBatch()
    {
//        $entireInstances = EntireInstance::where(function ($query) {
//            $query->where('status', 'INSTALLING')->orWhere('status', 'INSTALLED');
//        })->pluck('identity_code');

        $qrCodeContents = [];
//        foreach ($entireInstances as $entireInstance) {
//            $qrCodeContents[] = QrCode::format('png')->size(512)->encoding('UTF-8')->errorCorrection('H')->generate(json_encode($entireInstance, 256));
//        }

        $warehouseBatchReports = WarehouseBatchReport::with([
            'EntireInstance',
            'EntireInstance.EntireModel',
            'EntireInstance.Category',
        ])->get();

        return view($this->view('scanInBatch'))
            ->with('warehouseBatchReports', $warehouseBatchReports)
            ->with('qrCodeContents', $qrCodeContents);
    }

    /**
     * 扫码入所
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postScanInBatch(Request $request)
    {
        try {
            $identityCode = $request->get('qrCodeContent');

            # 获取该设备的检修单（未完成）
            $unFixWorkflowSerialNumber = FixWorkflow::where('entire_instance_identity_code', $identityCode)->whereNotIn('status', ['FIXED'])->first(['serial_number']);

            # 检查是否重复
            $repeat = WarehouseBatchReport::where('entire_instance_identity_code', $identityCode)->first();
            if ($repeat) return Response::make('数据重复', 403);

            # 检查该设备状态是否是已安装
            $isInstalled = EntireInstance::where('identity_code', $identityCode)->whereIn('status', ['INSTALLED', 'INSTALLING'])->first();
            if (!$isInstalled) return Response::make('该设备未安装', 500);

            $warehouseBatchReport = new WarehouseBatchReport;
            $warehouseBatchReport->entire_instance_identity_code = $identityCode;
            $warehouseBatchReport->fix_workflow_serial_number = $unFixWorkflowSerialNumber ? $unFixWorkflowSerialNumber->serial_number : null;
            $warehouseBatchReport->saveOrFail();

            return Response::make('扫码成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 清空批量表
     */
    public function postCleanBatch()
    {
        DB::table('warehouse_batch_reports')->truncate();
    }

    /**
     * 生成检修单
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function postMakeFixWorkflow(Request $request)
    {
        try {
            $newFixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW');
            DB::transaction(function () use ($newFixWorkflowSerialNumber, $request) {
                # 验证该整件下是否存在未完成的工单
                $unFixed = FixWorkflow::where('entire_instance_identity_code', $request->get('entireInstanceIdentityCode'))->whereNotIn('status', ['FIXED'])->count('id');
                if ($unFixed) throw new \Exception('该设备存在未完成的检修单');

                $entireInstance = EntireInstance::where('identity_code', $request->get('entireInstanceIdentityCode'))->firstOrFail();

                # 插入检修单
                $fixWorkflow = new FixWorkflow;
                $fixWorkflow->fill([
                    'entire_instance_identity_code' => $entireInstance->identity_code,
                    'status' => 'FIXING',
                    'processor_id' => session()->get('processor_id'),
                    'serial_number' => $newFixWorkflowSerialNumber,
                    'stage' => 'PART',
                ])->saveOrFail();

                # 修改整件实例中检修单序列号、状态和在库状态
                $entireInstance->fill([
                    'fix_workflow_serial_number' => $newFixWorkflowSerialNumber,
                    'status' => 'FIXING',
                    'in_warehouse' => false
                ])->saveOrFail();

                # 修改实例中部件的状态
                DB::table('part_instances')
                    ->where('entire_instance_identity_code', $entireInstance->identity_code)
                    ->update(['status' => 'FIXING']);

                # 添加批量表中对应的内容
                DB::table('warehouse_batch_reports')->where('entire_instance_identity_code', $request->get('entireInstanceIdentityCode'))->update(['fix_workflow_serial_number' => $newFixWorkflowSerialNumber]);
            });

            return Response::make('创建成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 404);
        }
    }

    /**
     * 删除批量单项
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function postDeleteBatch(Request $request)
    {
        DB::table('warehouse_batch_reports')->where('entire_instance_identity_code', $request->get('entireInstanceIdentityCode'))->delete();
        return Response::make('删除成功');
    }

    /**
     * 批量入所
     */
    public function postInBatch()
    {
        try {
            $warehouseBatchReports = WarehouseBatchReport::with('EntireInstance')->get();
            $repeat = \App\Facades\WarehouseReport::inBatch($warehouseBatchReports);
            if ($repeat) {
                $repeatStr = '';
                foreach ($repeat as $item) {
                    $serialNumber = $item->serial_number ? "所编号：{$item->serial_number}" : "";
                    $repeatStr .= "{$serialNumber}\r\n厂编号：{$item->factory_device_code}";
                }
                throw new \Exception(count($repeat) . "条重复入所，已跳过\r\n{$repeatStr}");
            }

            return Response::make('批量入所成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 批量生成检修单
     */
    public function postMakeFixWorkflowBatch()
    {
        try {
            $warehouseBatchReports = WarehouseBatchReport::with('EntireInstance')->get();

            $newFixWorkflowSerialNumber = Code::makeSerialNumber('FIX_WORKFLOW');
            $i = 0;
            $fail = [];
            DB::transaction(function () use ($newFixWorkflowSerialNumber, $warehouseBatchReports, &$fail, &$i) {
                foreach ($warehouseBatchReports as $warehouseBatchReport) {
                    $newFixWorkflowSerialNumber = $newFixWorkflowSerialNumber . ++$i;

                    # 验证该整件下是否存在未完成的工单
                    $unFixed = FixWorkflow::where('entire_instance_identity_code', $warehouseBatchReport->entire_instance_identity_code)->whereNotIn('status', ['FIXED'])->count('id');
                    if ($unFixed) {
                        $fail[] = $warehouseBatchReport->EntireInstance;
                        continue;
                    }

                    $entireInstance = EntireInstance::where('identity_code', $warehouseBatchReport->entire_instance_identity_code)->firstOrFail();

                    # 插入检修单
                    $fixWorkflow = new FixWorkflow;
                    $fixWorkflow->fill([
                        'entire_instance_identity_code' => $warehouseBatchReport->entire_instance_identity_code,
                        'status' => 'FIXING',
                        'processor_id' => session()->get('processor_id'),
                        'serial_number' => $newFixWorkflowSerialNumber,
                        'stage' => 'PART',
                    ])->saveOrFail();

                    # 修改整件实例中检修单序列号、状态和在库状态
                    $entireInstance->fill([
                        'fix_workflow_serial_number' => $newFixWorkflowSerialNumber,
                        'status' => 'FIXING',
                        'in_warehouse' => false
                    ])->saveOrFail();

                    # 修改实例中部件的状态
                    DB::table('part_instances')
                        ->where('entire_instance_identity_code', $entireInstance->identity_code)
                        ->update(['status' => 'FIXING']);

                    # 添加批量表中对应的内容
                    DB::table('warehouse_batch_reports')->where('entire_instance_identity_code', $warehouseBatchReport->entire_insatance_identity_code)->update(['fix_workflow_serial_number' => $newFixWorkflowSerialNumber]);
                }
            });

            if ($fail) {
                $failStr = '';
                foreach ($fail as $item) {
                    $serialNumber = $item->serial_number ? "所编号：{$item->serial_number}" : "";
                    $failStr .= "{$serialNumber}\r\n厂编号：{$item->factory_device_code}";
                }
                throw new \Exception(count($fail) . "条存在未完成的检修单，已跳过\r\n{$failStr}");
            }

            return Response::make('批量生成检修单成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 404);
        }
    }
}
