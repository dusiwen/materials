<?php

namespace App\Http\Controllers;

use App\Facades\Code;
use App\Http\Requests\Request;
use App\Model\Account;
use App\Model\Category;
use App\Model\EntireInstance;
use App\Model\EntireModel;
use App\Model\FixWorkflow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * 系统首页
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //出入库统计->获取出入库时间
        $date1 = date("Y-m-d",time());//获取当前月日
        $date2 = date("Y-m-d",strtotime("-1 day"));//获取昨天月日
        $date3 = date("Y-m-d",strtotime("-2 day"));//获取前天月日
        //出入库统计->获取入库数量
        $stockinsum1 = DB::table("stockincensus")->where("time",$date1)->get(["sum"])->toArray();//获取当前月日入库数量
        $stockinsum2 = DB::table("stockincensus")->where("time",$date2)->get(["sum"])->toArray();//获取前一天月日入库数量
        $stockinsum3 = DB::table("stockincensus")->where("time",$date3)->get(["sum"])->toArray();//获取前两天月日入库数量
        if (empty($stockinsum1["0"]->sum)){
            $stockinsum1 = "0";
        }else{
            $stockinsum1 = $stockinsum1["0"]->sum;
        }
        if (empty($stockinsum2["0"]->sum)){
            $stockinsum2 = "0";
        }else{
            $stockinsum2 = $stockinsum2["0"]->sum;
        }
        if (empty($stockinsum3["0"]->sum)){
            $stockinsum3 = "0";
        }else{
            $stockinsum3 = $stockinsum3["0"]->sum;
        }
        //出入库统计->获取出库数量
        $stockoutsum1 = DB::table("stockoutcensus")->where("time",$date1)->get(["sum"])->toArray();//获取当前月日入库数量
        $stockoutsum2 = DB::table("stockoutcensus")->where("time",$date2)->get(["sum"])->toArray();//获取前一天月日入库数量
        $stockoutsum3 = DB::table("stockoutcensus")->where("time",$date3)->get(["sum"])->toArray();//获取前两天月日入库数量
        if (empty($stockoutsum1["0"]->sum)){
            $stockoutsum1 = "0";
        }else{
            $stockoutsum1 = $stockoutsum1["0"]->sum;
        }
        if (empty($stockoutsum2["0"]->sum)){
            $stockoutsum2 = "0";
        }else{
            $stockoutsum2 = $stockoutsum2["0"]->sum;
        }
        if (empty($stockoutsum3["0"]->sum)){
            $stockoutsum3 = "0";
        }else{
            $stockoutsum3 = $stockoutsum3["0"]->sum;
        }


        //物资盘点->获取物资名称
        $materials = DB::table("materials")->where("sum","!=","NULL")->get(["MaterialName"])->toArray();
        $MaterialName = [];
        foreach ($materials as $k=>$v){
            $MaterialName[] = $v->MaterialName;
        }
//        dd($MaterialName);
//        dd(array_values($MaterialName));
        //物资盘点->获取物资数量
        $material = DB::table("materials")->where("sum","!=","NULL")->get(["sum"])->toArray();
        $MaterialSum = [];
        foreach ($material as $k=>$v){
            $MaterialSum[] = $v->sum;
        }
        //物资盘点->获取物资重量
        $materialweight = DB::table("materials")->where("sum","!=","NULL")->get(["EachWeight"])->toArray();
        $MaterialEachWeight = [];
        foreach ($materialweight as $k=>$v){
            $MaterialEachWeight[] = $v->EachWeight*$MaterialSum[$k];
        }

        //首页差异动态分析
        $count = DB::table("wm")->count();  //获取总条数
        //账物不一致数量
        $byz = DB::table("wm")->where("WMStatus","账物不一致")->count();
        //盘点正常数量
        $zc = DB::table("wm")->where("WMStatus","盘点正常")->count();
        //超期未出库
        $cq = DB::table("wm")->where("WMStatus","超期未出库")->count();
        return view('Index.index')
            ->with("date1", $date1)
            ->with("date2", $date2)
            ->with("date3", $date3)
            ->with("stockinsum1", $stockinsum1)
            ->with("stockinsum2", $stockinsum2)
            ->with("stockinsum3", $stockinsum3)
            ->with("stockoutsum1", $stockoutsum1)
            ->with("stockoutsum2", $stockoutsum2)
            ->with("stockoutsum3", $stockoutsum3)
            ->with("byz", $byz)
            ->with("zc", $zc)
            ->with("cq", $cq)
            ->with("MaterialName", json_encode($MaterialName,256))
            ->with("MaterialSum", json_encode($MaterialSum,256))
            ->with("MaterialEachWeight", json_encode($MaterialEachWeight,256));
    }

    public function test()
    {

    }

    public function onlyOnceFixed()
    {

    }

    public function readPart()
    {
    }

    public function readEntire()
    {
    }

    public function entireInstanceFixed($categoryUniqueCode, $entireModelUniqueCode)
    {

    }

    public function entireInstanceFixing($categoryUniqueCode, $entireModelUniqueCode)
    {

    }

    public function entireInstanceInstalled($categoryUniqueCode, $entireModelUniqueCode)
    {

    }

}
