<?php

namespace App\Http\Controllers\Warehouse;

use App\Facades\Code;
use App\Http\Controllers\Controller;
use App\Model\EntireInstance;
use App\Model\FixWorkflow;
use App\Model\stcokin;
use App\Model\WarehouseBatchReport;
use App\Model\WarehouseReport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use function Complex\asec;

class ReportController extends Controller
{
    /**
     * 入库单列表页
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //入库单页面筛选功能
        $stockin = DB::table("stockin");
        if ($request->get('project')) $stockin->where("StockIn_ProjectName",$request->get("project"));
        if ($request->get('MaterialName')) $stockin->where("StockIn_MaterialName",$request->get("MaterialName"));
        if ($request->get('stockin_type')) $stockin->where("StockIn_Type",$request->get("stockin_type"));
//        if ($request->get('updated_at')) $stockin->where("StockIn_ProjectName",$request->get("project"));
        if ($request->get('updated_at')){
            $time = explode("~",$request->get("updated_at"));
//            var_dump($time);
            $begin_time = $time[0];
            $end_time = $time[1];
            $time = array([$begin_time,$end_time]);
            $stockin->whereBetween("StockIn_times",$time);
        }
//        $stockin = $stockin->orderBy("id","desc")->paginate();
        $stockin = $stockin->orderBy("id","desc")->get()->toArray();
        //入库单列表页(将相同time的分在同一数组中)

        $stockinByTime = [];
        foreach ($stockin as $k=>$v){
            key_exists($v->time, $stockinByTime) ? array_push($stockinByTime[$v->time], $v) : $stockinByTime[$v->time] = [$v];

        }
//        $i = DB::table("stockin")->count();
//        var_dump($stockinByTime);
        return view($this->view())
//            ->with('warehouseReports', $warehouseReports)
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
     * 打印入库单,打印出库单
     * Display the specified resource.
     *
     * @param string $serialNumber
     * @return \Illuminate\Http\Response
     */
    public function show($time)
    {
        try {
            $types = \request()->get('types');
            if ($types =="stockin"){
                $stockin = DB::table("stockin")->where("time",$time)->orderBy("id","desc")->get()->toArray();
                $StockIn_time = $stockin[0]->StockIn_time;//获取入库时间
                $StockIn_Number = 0;
                $StockIn_Sum = 0;
                foreach ($stockin as $k=>$v){
                    $StockIn_Number+=$v->StockIn_Number;//获取总数量
                    $StockIn_Sum+=$v->StockIn_Sum;//获取总金额
                }
                switch (\request()->get('type')) {
                    case 'print':
                        $view = view($this->view('print'));
                        break;
                    default:
                        $view = view($this->view());
                        break;
                }

                return $view->with('stockin', $stockin)
                    ->with('types', $types)
                    ->with('StockIn_Number', $StockIn_Number)
                    ->with('StockIn_Sum', $StockIn_Sum)
                    ->with('StockIn_time', $StockIn_time);
            }elseif ($types == "stockout"){
                $stockout = DB::table("stockout")->where("time",$time)->orderBy("id","desc")->get()->toArray();
                $StockOut_time = $stockout[0]->StockOut_Time;//获取入库时间
                $StockOut_Number = 0;
                $StockOut_Sum = 0;
                foreach ($stockout as $k=>$v){
                    $StockOut_Number+=$v->StockOut_Number;//获取总数量
                    $StockOut_Sum+=$v->StockOut_Sum;//获取总金额
                }
                switch (\request()->get('type')) {
                    case 'print':
                        $view = view($this->view('print'));
                        break;
                    default:
                        $view = view($this->view());
                        break;
                }

                return $view->with('stockout', $stockout)
                    ->with('types', $types)
                    ->with('StockOut_Number', $StockOut_Number)
                    ->with('StockOut_Sum', $StockOut_Sum)
                    ->with('StockOut_time', $StockOut_time);
            }

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
     * 物资入库操作
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $times = time();
            $data = date("Y-m-d",$times);
            //获取入库表time字段,添加入库时间戳和年-月-日,生成批次号
            $StockIn_Batchs =DB::table("stockin")->orderByDesc("StockIn_Batch")->first();
            if (empty($StockIn_Batchs->StockIn_Batch)){
                $StockIn_Batch = "0000000001";
            }else{
                $StockIn_Batch = $StockIn_Batchs->StockIn_Batch+1;
                $StockIn_Batch = str_pad($StockIn_Batch,10,"0",STR_PAD_LEFT);
            }
            $date = DB::table("stockin")->where("id",$id)->get(); //获取入库单时间
            DB::table("stockin")->where("time",$date[0]->time)->update(["StockIn_time"=>$times,"StockIn_times"=>$data,"StockIn_Batch"=>$StockIn_Batch]);//添加入库时间戳
            $StockIn_MaterialCode = DB::table("stockin")->where("time",$date[0]->time)->get(); //获取物资编码

//            $mt_rand = mt_rand(360000000,369999999);//生成9位盘点凭证号(最多9位)
            //获取最后一条数据的盘点凭证号 存在盘点凭证号 不存在=360000001
            $rand = DB::table("wm")->orderByDesc("WMcode")->first();
            if (empty($rand->WMcode)){
                $mt_rand = "360000001";
            }else{
                $mt_rand = $rand->WMcode+1;
            }


            //1:将物资对应的数量存入物资表中(materials)
            //2:生成盘点表
            foreach ($StockIn_MaterialCode as $k=>$v){
                //1:将物资对应的数量存入物资表中(materials)
                $StockIn_Number = $v->StockIn_Number;//获取入库单中对应物资的数量
                $sumss = DB::table("materials")->where("MaterialCode",$v->StockIn_MaterialCode)->get();//获取物资表中对应物资的数量
                $sumsss = intval($StockIn_Number)+intval($sumss[0]->sum);
                DB::table("materials")->where("MaterialCode",$v->StockIn_MaterialCode)->update(["sum"=>$sumsss]);//更改对应物资的数量(可能多物资)

                //2:生成盘点表(可能多物资)
                //获取最后一条数据的物资编码 存在物资编码 不存在=500000001
                $code = DB::table("wm")->orderByDesc("MaterialsCode")->first();
                if (empty($code->MaterialsCode)){
                    $MaterialsCode = "500000001";
                }else{
                    $MaterialsCode = $code->MaterialsCode+1;
                }
                DB::table("wm")->insert(["Company"=>$v->StockIn_Units,"WMcode"=>$mt_rand,"MaterialsCode"=>$MaterialsCode,"MaterialsDescribe"=>$v->StockIn_MaterialName,"StorageType"=>"G01","Positions"=>"G01-010101","Unit"=>$v->StockIn_Unit,"WarehouseNumber"=>"EBA","Number"=>$v->StockIn_Number,"Location"=>"YEBA","WMDate"=>$times,"WMDates"=>$data,"time"=>$date[0]->time,"pid"=>$v->id]);//入库单生成盘点表

            }
            DB::table("stockin")->where("time",$date[0]->time)->update(["StockIn_Status"=>"已入库"]);//入库后修改入库状态状态
//            $StockIn_EachWeight = $date[0]->StockIn_EachWeight;  //获取入库单物资单个重量
//            $WMNumber = 5.7/$StockIn_EachWeight;  //根据入库单单个重量算出盘点数量

            //入库成功后将入库统计表stockincensus数量+1
            $stockincensus = DB::table("stockincensus")->orderBy('id', 'desc')->first();
            if (!empty($stockincensus)){
                if ($stockincensus->time == $data){
                    //存在当前入库日期将入库数量+1
                    $sum = DB::table("stockincensus")->where("time",$data)->select("sum")->get()->toArray();
                    $sums =$sum[0]->sum;
                    DB::table("stockincensus")->where("time",$data)->update(["sum"=>$sums+1]);
                }else{
                    //不存在则创建点当前日期并将数量设置为1
                    DB::table("stockincensus")->insert(["time"=>$data,"sum"=>"1"]);
                }
            }else{
                DB::table("stockincensus")->insert(["time"=>$data,"sum"=>"1"]);
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
            $exceptionmessage = $exception->getMessage();
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 入库单冲销(根据time删除该物资对应的托盘信息)(可能一对多)
     * Remove the specified resource from storage.
     *
     * @param int $time
     * @return \Illuminate\Http\Response
     */
    public function destroy($time)
    {
        try {

            $Materials= DB::table('stockin')->where('time',$time)->get()->toArray();//获取所选物资信息
            //获取每个物资的pid
            $pid = [];
            foreach ($Materials as $k=>$v){
                $pid[] = $v->pid;
            }
//            return Response::make($pid,404);
            //获取每个物资对应的托盘id,及该物质在该托盘上的数量
            foreach ($pid as $k=>$v) {
                $stockinnum[] = DB::table("stockinnum")->where("MaterialTestId",$v)->select("TrayId","MaterialNum")->get()->toArray();
            }
//            return Response::make($stockinnum,404);
            //将二位数组转换为一维数组
            $stockinnum = array_reduce($stockinnum, function ($result, $value) {
                return array_merge($result, array_values($value));
            }, array());

//            return Response::make($stockinnum,404);
            //重组数组
            $i = 0;
            foreach ($stockinnum as $k=>$v){
                $TrayId = $stockinnum[$i]->TrayId;
                $MaterialNum = $stockinnum[$i]->MaterialNum;
                $Tray[] = $TrayId;
                $Num[] = $MaterialNum;
                $i++;
            }
            $nums = array_combine($Tray,$Num);  //重组数组,将$Tray的值作为新数组的键名、$Num的值作为新数组的值
//            return Response::make($nums,404);
            //遍历删除物资对应的托盘数量
            foreach ($nums as $k=>$v){
                $weight = DB::table("tray")->where("id",$k)->select("weight")->get()->toArray();//获取所选托盘的重量
                $ResidueWeight = DB::table("tray")->where("id",$k)->select("ResidueWeight")->get()->toArray();//获取所选托盘的剩余重量
                $MaterialCode = DB::table("tray")->where("id",$k)->select("MaterialCode")->get()->toArray();//获取所选托盘的物资编码
                $StockIn_EachWeight = DB::table("materials")->where("MaterialCode",$MaterialCode[0]->MaterialCode)->get()->toArray();//获取物资的每个重量
                DB::table("tray")->where("id",$k)->update(["weight"=>($weight[0]->weight-$StockIn_EachWeight[0]->EachWeight*$v),"ResidueWeight"=>($ResidueWeight[0]->ResidueWeight+$StockIn_EachWeight[0]->EachWeight*$v)]); //选择对应的托盘,改变重量,剩余重量
                //如果weight=0 则认为是空托盘,项目名称,物资编码,物资名称设为空
                $w = DB::table("tray")->where("id",$k)->select("weight")->get()->toArray();//再次获取所选托盘的重量
                if ($w[0]->weight == 0){
                    DB::table("tray")->where("id",$k)->update(["ProjectName"=>NULL,"MaterialCode"=>NULL,"MaterialName"=>NULL,"weight"=>NULL]);
                }
            }
            //删除stockinnum表中物资对应的条数,删除入库单中对应的物资
            foreach ($pid as $k=>$v) {
                DB::table("stockinnum")->where("MaterialTestId",$v)->delete();
                DB::table("stockin")->where("pid",$v)->delete();
            }

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
