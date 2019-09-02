<?php

namespace App\Http\Controllers;

use App\Http\Requests\V1\CategoryRequest;
use App\Model\Category;
use App\Model\EntireModel;
use App\Model\stockouttest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * 添加物资出库单页面
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::orderByDesc('id')->paginate();


        $project = DB::table("project")->get()->toArray();  //获取所有项目名称
        $stockout_type = DB::table("stockout_type")->get()->toArray();  //获取出库类型
        $stockouttest = DB::table("stockouttest")->orderBy('id','desc')->get()->toArray();  //获取添加的物资列表中的物资
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
            $tray = DB::table("tray")->where('MaterialCode',$MaterialCodes)->get()->toArray();
//                dd($tray);
//            $trays = DB::table("tray")->where('MaterialCode',NULL)->get()->toArray();
//            foreach ($trays as $k=>$v){
//                $tray[] =$v;
////                    dd($v);
//            }
//            $tray= array_unique($tray, SORT_REGULAR);
//                dd($tray);
        }else{
            $tray = DB::table("tray")->get()->toArray();
        }
        $i = 1;
        return view('Category.index', ['categories' => $categories,
            "MaterialName" => $MaterialName,
            "stockout_type"=>$stockout_type,
            "tray"=>$tray,
            "stockouttest"=>$stockouttest,
            "EachWeight"=>$EachWeight,
            "i"=>$i,
            "project"=>$project]);
    }

    /**
     *保存
     *清除物资列表数据,并返回出库单列表页面
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $time =time();
        DB::table("stockouttest")->update(["time"=>$time]);
        $stockouttest =stockouttest::select(["StockOut_Units","StockOut_StorageLocation","StockOut_OrderNumber","StockOut_ProjectName","StockOut_ContractNumber","StockOut_RecipientsUnit","StockOut_AccountingNumber","StockOut_MaterialsNumber","StockOut_Time","StockOut_ReservedNumber","StockOut_MaterialCode","StockOut_MaterialName","StockOut_Batch","StockOut_Number","StockOut_Price","StockOut_Sum","StockOut_Unit","StockOut_Remark","StockOut_Principal","StockOut_Custodian","StockOut_Picking","StockOut_Status","StockOut_Type","StockOut_EachWeight","StockOut_Weight","time","pid","created_at","updated_at"])->get()->toArray();
        if (empty($stockouttest)){
            return Response::make("数据不存在",404);
        }
        DB::table('stockout')->insert($stockouttest);//插入数据
        //将托盘重量=0的托盘清除信息
        $trays = DB::table("tray")->get()->toArray();
        foreach ($trays as $k=>$v){
            $weights = $v->weight;  //获取托盘重量
            $id = $v->id;  //获取托盘id
            $max = $v->max;  //获取托盘承重最大值
            if (empty($weights)){
                DB::table("tray")->where("id",$id)->update(["ProjectName"=>NULL,"MaterialCode"=>NULL,"MaterialName"=>NULL,"weight"=>NULL,"ResidueWeight"=>$max,"times"=>NULL]);
            }
        }
        DB::table("stockouttest")->delete();//清空表
//        return view('Category.create');
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
            $v = Validator::make($request->all(), CategoryRequest::$RULES, CategoryRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $category = new Category;
            $category->fill($request->all())->saveOrFail();

            return Response::make('新建成功');
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
     * 保存并返回出库单列表
     * Display the specified resource.
     *
     * @param string $categoryUniqueCode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        //
    }

    /**
     * 获取所有智能托盘信息(出库管理)
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        try {
//            $entireModel = EntireModel::findOrFail($id);
//            $partModels = PivotEntireModelAndPartModel::where('entire_model_unique_code', $entireModel->unique_code)->pluck('part_model_unique_code');
//            $categories = Category::orderByDesc('id')->pluck('name', 'unique_code');



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
//                ->with('entireModel', $entireModel)
//                ->with('categories', $categories)
                ->with('tray', $tray)
                ->with('MaterialName', $MaterialName)
                ->with('EachWeight', $EachWeight)
                ->with('i', $i);
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
     * 添加对应物资到物资列表(出库管理)
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
            $StockIn_Weight =$EachWeight*($request->input("StockOut_Number")); //根据每个重量计算总重量
            $StockIn_Sum = $request->input("StockOut_Number")*$request->input("StockOut_Price");  //计算物资总金额



//            $Numbers = array_filter($Number); //去除空数组
            if (empty($request->input("StockOut_Number"))){
                return Response::make('请填写物资总数量', 404);
            }
            if (empty($request->input("StockOut_Price"))){
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
                return Response::make("请填写下架数量", 404);
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
            if ($sum !=$request->input("StockOut_Number")){
                return Response::make("下架数量与物资总数量不符",404);
            }

//            if (array_combine($trayid,$Numbers)){
//                $trays = array_combine($trayid,$Numbers);
//            }else{
//                return Response::make("请在所选托盘上填写数量",404);
//            }

            $trays = array_combine($trayid,$Numbers);  //重组数组,将$trayid的值作为新数组的键名、$Numbers的值作为新数组的值
//            return Response::make($trays,404);
            $time = time();
            //将物资列表中的数据存入stockouttest表中(返回id)
            $MaterialTestId = DB::table("stockouttest")->insertGetId(["StockOut_Remark"=>$request->input("StockOut_Remark"),"StockOut_Type"=>$request->input("StockOut_Type"),"StockOut_MaterialCode"=>$MaterialCode,"StockOut_MaterialName"=>$MaterialName,"StockOut_Unit"=>$unit,"StockOut_Number"=>$request->input("StockOut_Number"),"StockOut_Price"=>$request->input("StockOut_Price"),"StockOut_Sum"=>$StockIn_Sum,"StockOut_EachWeight"=>$EachWeight,"StockOut_Weight"=>$StockIn_Weight,"StockOut_ProjectName"=>$project_name,"time"=>$time,"StockOut_Status"=>"扫码确认"]); //添加数据到出库单中
            DB::table("stockouttest")->where("id",$MaterialTestId)->update(["pid"=>$MaterialTestId]);//添加pid=id
            //选择对应的托盘,给予物资编码,物料凭证号,物资名称,剩余重量(可能多选)
            foreach ($trays as $k=>$v){
                $weight = DB::table("tray")->where("id",$k)->select("weight")->get()->toArray();//获取所选托盘的重量
                $ResidueWeight = DB::table("tray")->where("id",$k)->select("ResidueWeight")->get()->toArray();//获取所选托盘的剩余重量
                DB::table("tray")->where("id",$k)->update(["weight"=>($weight[0]->weight-$EachWeight*$v),"ResidueWeight"=>($ResidueWeight[0]->ResidueWeight+$EachWeight*$v),"times"=>$time]); //选择对应的托盘,给予物资编码,物资名称,物料凭证号,重量,剩余重量
                //如果托盘重量=0则清空该托盘上的信息
                $weights = DB::table("tray")->where("id",$k)->select("weight")->get()->toArray();//再次获取所选托盘的重量
                $max = DB::table("tray")->where("id",$k)->select("max")->get()->toArray();//获取所选托盘的剩余重量
                if (empty($weights[0]->weight)){
                    DB::table("tray")->where("id",$k)->update(["ProjectName"=>NULL,"MaterialCode"=>NULL,"MaterialName"=>NULL,"weight"=>NULL,"ResidueWeight"=>$max[0]->max,"times"=>NULL]);
                }
                //将所选物资对应的托盘id,上架数量,物资列表id存入stockoutnum表中(方便删除)
                DB::table("stockoutnum")->insert(["TrayId"=>$k,"MaterialNum"=>$v,"MaterialTestId"=>$MaterialTestId,"time"=>$time]);
            }

            $request->session()->forget("MaterialsId");//清除所选物资id
            return Response::make('选择成功');
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
     * 物资列表删除对应物资(出库管理)
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $Materials= DB::table('stockouttest')->where('id',$id)->get()->toArray();//获取所选物资信息
            $StockOut_EachWeight = $Materials[0]->StockOut_EachWeight;//获取物资每个重量
            $stockoutnum = DB::table("stockoutnum")->where("MaterialTestId",$id)->select("TrayId","MaterialNum")->get()->toArray();
            $i = 0;
            foreach ($stockoutnum as $k=>$v){
                $TrayId = $stockoutnum[$i]->TrayId;
                $MaterialNum = $stockoutnum[$i]->MaterialNum;
                $Tray[] = $TrayId;
                $Num[] = $MaterialNum;
                $i++;
            }
            $nums = array_combine($Tray,$Num);  //重组数组,将$Tray的值作为新数组的键名、$Num的值作为新数组的值
            //遍历删除物资对应的托盘数量
            foreach ($nums as $k=>$v){
                $weight = DB::table("tray")->where("id",$k)->select("weight")->get()->toArray();//获取所选托盘的重量
                $ResidueWeight = DB::table("tray")->where("id",$k)->select("ResidueWeight")->get()->toArray();//获取所选托盘的剩余重量
                DB::table("tray")->where("id",$k)->update(["weight"=>($weight[0]->weight+$StockOut_EachWeight*$v),"ResidueWeight"=>($ResidueWeight[0]->ResidueWeight-$StockOut_EachWeight*$v),"ProjectName"=>$Materials[0]->StockOut_ProjectName,"MaterialCode"=>$Materials[0]->StockOut_MaterialCode,"MaterialName"=>$Materials[0]->StockOut_MaterialName]); //选择对应的托盘,改变重量,剩余重量、返还对应托盘的项目名称,物资编码,物资名称
            }
            //删除stockoutnum表中物资对应的条数
            DB::table("stockoutnum")->where("MaterialTestId",$id)->delete();
            //删除stockouttest表中对应的物资
            DB::table("stockouttest")->where("id",$id)->delete();
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
}
