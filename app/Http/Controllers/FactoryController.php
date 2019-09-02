<?php

namespace App\Http\Controllers;

use App\Http\Requests\V1\FactoryRequest;
use App\Model\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FactoryController extends Controller
{
    /**
     * 出库单列表页
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {



        //出库单页面筛选功能
        $stockout = DB::table("stockout");
        if ($request->get('project')) $stockout->where("StockOut_ProjectName",$request->get("project"));
        if ($request->get('MaterialName')) $stockout->where("StockOut_MaterialName",$request->get("MaterialName"));
        if ($request->get('stockout_type')) $stockout->where("StockOut_Type",$request->get("stockout_type"));
//        if ($request->get('updated_at')) $stockout->where("StockOut_ProjectName",$request->get("project"));
        if ($request->get('updated_at')){
            $time = explode("~",$request->get("updated_at"));
//            var_dump($time);
            $begin_time = $time[0];
            $end_time = $time[1];
            $time = array([$begin_time,$end_time]);
            $stockout->whereBetween("StockOut_times",$time);
        }
//        $stockout = $stockout->orderBy("id","desc")->paginate();
        $stockout = $stockout->orderBy("id","desc")->get()->toArray();
        //出库单列表页(将相同time的分在同一数组中)
        $stockoutByTime = [];
        foreach ($stockout as $k=>$v){
            key_exists($v->time, $stockoutByTime) ? array_push($stockoutByTime[$v->time], $v) : $stockoutByTime[$v->time] = [$v];
        }
        return view('Factory.index', ["stockoutByTime"=>$stockoutByTime]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $viewName = \request()->ajax() ? 'create_ajax' : 'create';
        return view("Factory.{$viewName}");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            $v = Validator::make($request->all(), FactoryRequest::$RULES, FactoryRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $factory = new Factory;
            $factory->fill($request->all())->saveOrFail();

            return Response::make('新建成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
//             return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make('意外错误', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $factory = Factory::findOrFail($id);
            return view('Factory.edit', ['factory' => $factory]);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return back()->with('danger', '意外错误');
        }
    }

    /**
     * 物资出库操作
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $times = time();
            $data = date("Y-m-d",$times);
            //获取出库表time字段,添加出库时间戳和年-月-日,生成批次号
            $StockOut_Batchs =DB::table("stockout")->orderByDesc("StockOut_Batch")->first();
            if (empty($StockOut_Batchs->StockOut_Batch)){
                $StockOut_Batch = "0000000001";
            }else{
                $StockOut_Batch = $StockOut_Batchs->StockOut_Batch+1;
                $StockOut_Batch = str_pad($StockOut_Batch,10,"0",STR_PAD_LEFT);
            }
            $date = DB::table("stockout")->where("id",$id)->get(); //获取出库单时间
            DB::table("stockout")->where("time",$date[0]->time)->update(["StockOut_time"=>$times,"StockOut_times"=>$data,"StockOut_Batch"=>$StockOut_Batch]);//添加出库时间戳
            $StockOut_MaterialCode = DB::table("stockout")->where("time",$date[0]->time)->get(); //获取物资编码

            //1:将物资对应的数量存入物资表中(materials)
            //2:修改盘点表
            foreach ($StockOut_MaterialCode as $k=>$v){
                //1:将物资对应的数量存入物资表中(materials)
                $StockOut_Number = $v->StockOut_Number;//获取出库单中对应物资的数量
                $sumss = DB::table("materials")->where("MaterialCode",$v->StockOut_MaterialCode)->get();//获取物资表中对应物资的数量
                $sumsss = intval($sumss[0]->sum)-intval($StockOut_Number);
                DB::table("materials")->where("MaterialCode",$v->StockOut_MaterialCode)->update(["sum"=>$sumsss]);//更改对应物资的数量(可能多物资)

                //2:修改盘点表(可能多物资)(空物资则删除该条数据)(如何确定是盘点表的哪一条数据?)
                $Number = DB::table("wm")->where("MaterialsDescribe",$v->StockOut_MaterialName)->get();//获取账面数量
                DB::table("wm")->where("MaterialsDescribe",$v->StockOut_MaterialName)->update(["Number"=>$Number[0]->Number-$date[0]->StockOut_Number]);//根据物资编码获取入库单对应的信息(1对多)(bug:存在相同的物资会都改变数量)
            }
            DB::table("stockout")->where("time",$date[0]->time)->update(["StockOut_Status"=>"已出库"]);//出库后修改出库状态状态
//            $StockOut_EachWeight = $date[0]->StockOut_EachWeight;  //获取出库单物资单个重量
//            $WMNumber = 5.7/$StockOut_EachWeight;  //根据出库单单个重量算出盘点数量

            //出库成功后将出库统计表stockoutcensus数量+1
            $stockoutcensus = DB::table("stockoutcensus")->orderBy('id', 'desc')->first();
            if (!empty($stockoutcensus)){
                if ($stockoutcensus->time == $data){
                    //存在当前出库日期将出库数量+1
                    $sum = DB::table("stockoutcensus")->where("time",$data)->select("sum")->get()->toArray();
                    $sums =$sum[0]->sum;
                    DB::table("stockoutcensus")->where("time",$data)->update(["sum"=>$sums+1]);
                }else{
                    //不存在则创建点当前日期并将数量设置为1
                    DB::table("stockoutcensus")->insert(["time"=>$data,"sum"=>"1"]);
                }
            }else{
                DB::table("stockoutcensus")->insert(["time"=>$data,"sum"=>"1"]);
            }

            return Response::make('出库成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            $exceptionmessage = $exception->getMessage();
            return Response::make('意外错误', 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($time)
    {
        try {
            $Materials= DB::table('stockout')->where('time',$time)->get()->toArray();//获取所选物资信息
            //获取每个物资的pid
            $pid = [];
            foreach ($Materials as $k=>$v){
                $pid[] = $v->pid;
            }
//            return Response::make($pid,404);
            //获取每个物资对应的托盘id,及该物质在该托盘上的数量
            foreach ($pid as $k=>$v) {
                $stockoutnum[] = DB::table("stockoutnum")->where("MaterialTestId",$v)->select("TrayId","MaterialNum")->get()->toArray();
            }
//            return Response::make($stockoutnum,404);
            //将二位数组转换为一维数组
            $stockoutnum = array_reduce($stockoutnum, function ($result, $value) {
                return array_merge($result, array_values($value));
            }, array());

//            return Response::make($stockoutnum,404);
            //重组数组
            $i = 0;
            foreach ($stockoutnum as $k=>$v){
                $TrayId = $stockoutnum[$i]->TrayId;
                $MaterialNum = $stockoutnum[$i]->MaterialNum;
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
                DB::table("stockoutnum")->where("MaterialTestId",$v)->delete();
                DB::table("stockout")->where("pid",$v)->delete();
            }

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
}
