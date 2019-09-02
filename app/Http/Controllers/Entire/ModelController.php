<?php

namespace App\Http\Controllers\Entire;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EntireModelRequest;
use App\Model\Category;
use App\Model\EntireModel;
use App\Model\PivotEntireModelAndPartModel;
use App\Model\stockintest;
use App\Model\Tray;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entireModels = EntireModel::with(['Category'])->orderByDesc('id')->paginate();
        return view($this->view('index'))
            ->with('entireModels', $entireModels);
    }

    public function view($viewName)
    {
        return "Entire.Model.{$viewName}";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderByDesc('id')->pluck('name', 'unique_code');
        return view($this->view('create'))
            ->with('categories', $categories);
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
//        try {
//            $v = Validator::make($request->all(), EntireModelRequest::$RULES, EntireModelRequest::$MESSAGES);
//            if ($v->fails()) return Response::make($v->errors()->first(), 422);
//
//            DB::transaction(function () use ($request) {
//                # 保存整件型号
//                $entireModel = new EntireModel;
//                $entireModel->fill($request->all())->saveOrFail();
//
//                # 保存整件型号与部件型号
//                if ($request->get("part_model_unique_code"))
//                {
//                    $partModels = [];
//                    foreach ($request->get('part_model_unique_code') as $item) {
//                        $partModels[] = [
//                            'entire_model_unique_code' => $request->get('unique_code'),
//                            'part_model_unique_code' => $item,
//                        ];
//                    }
//                    if (!DB::table('pivot_entire_model_and_part_models')->insert($partModels)) throw new \Exception('保存对应关系失败');
//                }
//            });
//
//            return Response::make('新建成功');
//        } catch (ModelNotFoundException $exception) {
//            return Response::make('数据不存在', 404);
//        } catch (\Exception $exception) {
//            $exceptionMessage = $exception->getMessage();
//            $exceptionLine = $exception->getLine();
//            $exceptionFile = $exception->getFile();
//            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
//            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
//            return Response::make($exceptionMessage, 500);
//        }
    }

    /**
     * 保存并返回入库单列表
     * 将stockintest表中的数据存入stockin表中并清空stockintest表
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $time =time();
            DB::table("stockintest")->update(["time"=>$time]);
            $stockintest =stockintest::select(["StockIn_Units","StockIn_SourceOfFund","StockIn_MaterialsNumber","StockIn_StorageLocation","StockIn_time","StockIn_Consignee","StockIn_OrderNumber","StockIn_AccountingNumber","StockIn_Supplier","StockIn_ContractNumber","StockIn_MaterialCode","StockIn_MaterialName","StockIn_Batch","StockIn_Unit","StockIn_Number","StockIn_Price","StockIn_Sum","StockIn_ProjectName","WBS","StockIn_Remark","StockIn_Status","StockIn_Type","StockIn_Principal","StockIn_Custodian","StockIn_EachWeight","StockIn_Weight","time","pid","created_at","updated_at"])->get()->toArray();
            if (empty($stockintest)){
                return Response::make('数据不存在', 404);
            }
            DB::table('stockin')->insert($stockintest);
//            DB::table('stockins')->insert(["time"=>$time]);
            DB::table("stockintest")->delete();//清空表
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make("意外错误", 500);
        }
    }

    /**
     * 获取所有智能托盘信息
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        try {
            $entireModel = EntireModel::findOrFail($id);
            $partModels = PivotEntireModelAndPartModel::where('entire_model_unique_code', $entireModel->unique_code)->pluck('part_model_unique_code');
            $categories = Category::orderByDesc('id')->pluck('name', 'unique_code');


            $project = DB::table("project")->get()->toArray();  //获取所有项目名称
            $stockin_type = DB::table("stockin_type")->get()->toArray();  //获取入库类型
            $stockintest = DB::table("stockintest")->orderBy('id','desc')->get()->toArray();  //获取添加的物资列表中的物资
            //将选择的物资存入session后,获取session值
            if (!empty($request->session()->get("MaterialsId"))){
                $MaterialsId = $request->session()->get("MaterialsId");//若不为空则传值
                $MaterialsIds= DB::table('materials')->where('id',$MaterialsId)->select("MaterialName")->get()->toArray();
                $MaterialName = $MaterialsIds[0]->MaterialName;//获取物资名称
            }else{
                $MaterialName = '';
                $EachWeight = '';
            }
            //根据不同的物资匹配出最适合的托盘,
            if (!empty($MaterialsId)){
                //智能匹配出最适合的托盘
                $MaterialCode= DB::table('materials')->where('id',$MaterialsId)->select("MaterialCode","EachWeight")->get()->toArray();
                $MaterialCodes = $MaterialCode[0]->MaterialCode;//获取物资编码
                $EachWeight = $MaterialCode[0]->EachWeight;//获取每个物资重量
                $tray = DB::table("tray")->where('MaterialCode',$MaterialCodes)->where('ResidueWeight','>',$EachWeight)->get()->toArray();
//                dd($tray);
                $trays = DB::table("tray")->where('MaterialCode',NULL)->get()->toArray();
//                $tray = Tray::where('MaterialCode',$MaterialCodes)->where('ResidueWeight','>',$EachWeight)->get()->toArray();
//                dd($tray);
//                $trays = Tray::all()->toArray();//获取所有托盘信息
//                dd($trays);
//                dd($trays);
                foreach ($trays as $k=>$v){
                    $tray[] =$v;
//                    dd($v);
                }
                $tray= array_unique($tray, SORT_REGULAR);
//                dd($tray);
            }else{
                $tray = DB::table("tray")->get()->toArray(); //获取所有托盘信息
            }
            $i = 1;
            return view($this->view('edit'))
                ->with('entireModel', $entireModel)
                ->with('categories', $categories)
                ->with('tray', $tray)
                ->with('stockin_type', $stockin_type)
                ->with('stockintest', $stockintest)
                ->with('project', $project)
                ->with('MaterialName', $MaterialName)
                ->with('EachWeight', $EachWeight)
                ->with('i', $i)
                ->with('partModels', $partModels);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
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
     * 添加对应物资到物资列表
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $MaterialsId = $request->session()->get("MaterialsId");//获取所选物资id
            if (empty($MaterialsId)){
                return Response::make('请选择物资', 404);
            }
            $Materials= DB::table('materials')->where('id',$MaterialsId)->get()->toArray();//获取所选物资信息
            $MaterialName = $Materials[0]->MaterialName;//获取物资名称
            $MaterialCode = $Materials[0]->MaterialCode;//获取物资编码
            $unit = $Materials[0]->unit;//获取物资单位
            $EachWeight = $Materials[0]->EachWeight;//获取物资每个重量
//            $StockIn_StorageType = $request->input("StorageType");  //获取仓储类型
            $project_name = $request->input("project_name");  //获取项目名称
            $WBS = DB::table("project")->where("project_name",$project_name)->get(["WBS"])->toArray();//获取WBS元素
            $StockIn_Weight =$EachWeight*($request->input("StockIn_Number")); //根据每个重量计算总重量
            $StockIn_Sum = $request->input("StockIn_Number")*$request->input("StockIn_Price");  //计算物资总金额



//            $Numbers = array_filter($Number); //去除空数组
            if (empty($request->input("StockIn_Number"))){
                return Response::make('请填写物资总数量', 404);
            }
            if (empty($request->input("StockIn_Price"))){
                return Response::make('请填写正确单价', 404);
            }
//            if (!empty($request->input("tray"))){
//                //数量不够选择多个托盘(未写)
//            }else{
//
//                return Response::make('请选择托盘', 404);
//            }
            if (empty($request->input("tray"))){
                return Response::make('请选择对应托盘', 404);
            }else{
                $trayid = $request->input('tray');//获取所选托盘的id
            }
            if (empty($request->input("Numbers"))){
                return Response::make("请填写上架数量", 404);
            }else{
                $Number = $request->input("Numbers");//获取不同托盘上的上架数量
                $sum = "0";
                //获取填写数量
                foreach ($Number as $v){
                    if ($v !=NULL){
                        $Numbers[] = $v;
                        $sum+=$v;
                    }

                }
            }
            //判断上架数量是否与中数量相等
            if ($sum !=$request->input("StockIn_Number")){
                return Response::make("上架数量与总物资总数量不符",404);
            }

//            if (array_combine($trayid,$Numbers)){
//                $trays = array_combine($trayid,$Numbers);
//            }else{
//                return Response::make("请在所选托盘上填写数量",404);
//            }

            $trays = array_combine($trayid,$Numbers);  //重组数组,将$trayid的值作为新数组的键名、$Numbers的值作为新数组的值
//            return Response::make($trays,404);
            $time = time();
            //将物资列表中的数据存入stockintest表中(返回id)
            $MaterialTestId = DB::table("stockintest")->insertGetId(["StockIn_Type"=>$request->input("StockIn_Type"),"StockIn_MaterialCode"=>$MaterialCode,"StockIn_MaterialName"=>$MaterialName,"StockIn_Unit"=>$unit,"StockIn_Number"=>$request->input("StockIn_Number"),"StockIn_Price"=>$request->input("StockIn_Price"),"StockIn_Sum"=>$StockIn_Sum,"StockIn_Remark"=>$request->input("StockIn_Remark"),"StockIn_EachWeight"=>$EachWeight,"StockIn_Weight"=>$StockIn_Weight,"StockIn_ProjectName"=>$project_name,"WBS"=>$WBS[0]->WBS,"time"=>$time,"StockIn_Status"=>"扫码确认"]); //添加数据到入库单中
            DB::table("stockintest")->where("id",$MaterialTestId)->update(["pid"=>$MaterialTestId]);//添加pid=id
            //选择对应的托盘,给予物资编码,物料凭证号,物资名称,剩余重量(可能多选)
            foreach ($trays as $k=>$v){
                $weight = DB::table("tray")->where("id",$k)->select("weight")->get()->toArray();//获取所选托盘的重量
                $ResidueWeight = DB::table("tray")->where("id",$k)->select("ResidueWeight")->get()->toArray();//获取所选托盘的剩余重量
                DB::table("tray")->where("id",$k)->update(["MaterialCode"=>$MaterialCode,"ProjectName"=>$project_name,"MaterialName"=>$MaterialName,"weight"=>($weight[0]->weight+$EachWeight*$v),"ResidueWeight"=>($ResidueWeight[0]->ResidueWeight-$EachWeight*$v),"times"=>$time]); //选择对应的托盘,给予物资编码,物资名称,物料凭证号,重量,剩余重量
                //将所选物资对应的托盘id,上架数量,物资列表id存入stockinnum表中(方便删除)
                DB::table("stockinnum")->insert(["TrayId"=>$k,"MaterialNum"=>$v,"MaterialTestId"=>$MaterialTestId,"time"=>$time]);
            }

            $request->session()->forget("MaterialsId");//清除所选物资id
            return Response::make('添加成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            return Response::make($exceptionMessage, 500);
        }
    }

    /**
     * 物资列表删除对应物资
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $Materials= DB::table('stockintest')->where('id',$id)->get()->toArray();//获取所选物资信息
            $StockIn_EachWeight = $Materials[0]->StockIn_EachWeight;//获取物资每个重量
            $stockinnum = DB::table("stockinnum")->where("MaterialTestId",$id)->select("TrayId","MaterialNum")->get()->toArray();//获取该物资对应的托盘id及该托盘对应的物资数量
            $i = 0;
            foreach ($stockinnum as $k=>$v){
                $TrayId = $stockinnum[$i]->TrayId;
                $MaterialNum = $stockinnum[$i]->MaterialNum;
                $Tray[] = $TrayId;
                $Num[] = $MaterialNum;
                $i++;
            }
            $nums = array_combine($Tray,$Num);  //重组数组,将$Tray的值作为新数组的键名、$Num的值作为新数组的值
            //遍历删除物资对应的托盘数量
            foreach ($nums as $k=>$v){
                $weight = DB::table("tray")->where("id",$k)->select("weight")->get()->toArray();//获取所选托盘的重量
                $ResidueWeight = DB::table("tray")->where("id",$k)->select("ResidueWeight")->get()->toArray();//获取所选托盘的剩余重量
                DB::table("tray")->where("id",$k)->update(["weight"=>($weight[0]->weight-$StockIn_EachWeight*$v),"ResidueWeight"=>($ResidueWeight[0]->ResidueWeight+$StockIn_EachWeight*$v)]); //选择对应的托盘,改变重量,剩余重量
                //如果weight=0 则认为是空托盘,项目名称,物资编码,物资名称设为空
                $w = DB::table("tray")->where("id",$k)->select("weight")->get()->toArray();//再次获取所选托盘的重量
                $max = DB::table("tray")->where("id",$k)->select("max")->get()->toArray();//获取所选托盘的剩余重量
                if ($w[0]->weight == 0){
                    DB::table("tray")->where("id",$k)->update(["ProjectName"=>NULL,"MaterialCode"=>NULL,"MaterialName"=>NULL,"weight"=>NULL,"ResidueWeight"=>$max[0]->max,"times"=>NULL]);
                }
            }
            //删除stockinnum表中物资对应的条数
            DB::table("stockinnum")->where("MaterialTestId",$id)->delete();
            //删除stockintest表中对应的物资
            DB::table("stockintest")->where("id",$id)->delete();
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
