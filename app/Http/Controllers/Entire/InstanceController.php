<?php

namespace App\Http\Controllers\Entire;

use App\Facades\WarehouseReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EntireInstanceRequest;
use App\Model\Account;
use App\Model\Category;
use App\Model\EntireInstance;
use App\Model\EntireModel;
use App\Model\PivotEntireModelAndPartModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * Class InstanceController
 * @example 01：入库单 02：出库单 03：检修工单
 * @package App\Http\Controllers\Entire
 */
class InstanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # 获取所有种类列表
        $categories = Category::all();

        # 获取对应类型列表
        $entireModels = EntireModel::where("category_unique_code", \request()->get("categoryUniqueCode", $categories[0]["unique_code"]))->get();

        # 计算获取数据类型时间起点和终点
        $type = request()->get('updatedAt', 0) > 12 ? 12 : request()->get('updatedAt', 0);
        if ($type) {
            # 获取若干月数据
            $time = [Carbon::now()->subMonth(request()->get('updatedAt', 0))->toDateString(), Carbon::parse("+1day -1second")->toDateString()];
        } else {
            # 获取当月数据
            $time = [date('Y-m-01'), Carbon::parse("+1day -1second")->toDateString()];
        }

        # 动态获取设备状态统计（当月）
        $currentMonth = [date('Y-m-01'), Carbon::parse("+1day -1second")->toDateString()];
        $categoryUniqueCode = request()->get('categoryUniqueCode', 'S03');
        $using = EntireInstance::with(['Category'])->whereBetween('updated_at', $currentMonth)->where('category_unique_code', $categoryUniqueCode)->whereIn('status', ['INSTALLING', 'INSTALLED'])->count('id');
        $fixed = EntireInstance::with(['Category'])->whereBetween('updated_at', $currentMonth)->where('category_unique_code', $categoryUniqueCode)->where('status', 'FIXED')->where('in_warehouse', true)->count('id');
        $returnFactory = EntireInstance::with(['Category'])->whereBetween('updated_at', $currentMonth)->where('category_unique_code', $categoryUniqueCode)->where('status', 'RETURN_FACTORY')->count('id');
        $fixing = EntireInstance::with(['Category'])->whereBetween('updated_at', $currentMonth)->where('category_unique_code', $categoryUniqueCode)->where('status', 'FIXING')->count('id');
        $total = $using + $fixed + $fixing + $returnFactory;
        $deviceDynamicStatusCurrentMonth = [
            ["label" => "在用", "value" => $using],
            ["label" => "维修", "value" => $fixing],
            ["label" => "送检", "value" => $returnFactory],
            ["label" => "备用", "value" => $fixed]
        ];
        $deviceDynamicStatusCurrentMonth = json_encode([$total, $deviceDynamicStatusCurrentMonth], 256);

        # 获取近三个月数据
        $nearlyThreeMonth = [Carbon::now()->subMonth(3)->toDateString(), Carbon::parse("+1 day -1 second")->toDateString()];
        $categoryUniqueCode = request()->get('categoryUniqueCode', 'S03');
        $using = EntireInstance::with(['Category'])->whereBetween('updated_at', $nearlyThreeMonth)->where('category_unique_code', $categoryUniqueCode)->whereIn('status', ['INSTALLING', 'INSTALLED'])->count('id');
        $fixed = EntireInstance::with(['Category'])->whereBetween('updated_at', $nearlyThreeMonth)->where('category_unique_code', $categoryUniqueCode)->where('status', 'FIXED')->where('in_warehouse', true)->count('id');
        $returnFactory = EntireInstance::with(['Category'])->whereBetween('updated_at', $nearlyThreeMonth)->where('category_unique_code', $categoryUniqueCode)->where('status', 'RETURN_FACTORY')->count('id');
        $fixing = EntireInstance::with(['Category'])->whereBetween('updated_at', $nearlyThreeMonth)->where('category_unique_code', $categoryUniqueCode)->where('status', 'FIXING')->count('id');
        $total = $using + $fixed + $fixing + $returnFactory;
        $deviceDynamicStatusNearlyThreeMonth = [
            ["label" => "在用", "value" => $using],
            ["label" => "维修", "value" => $fixing],
            ["label" => "送检", "value" => $returnFactory],
            ["label" => "备用", "value" => $fixed]
        ];
        $deviceDynamicStatusNearlyThreeMonth = json_encode([$total, $deviceDynamicStatusNearlyThreeMonth], 256);

        $entireInstanceBuilder = EntireInstance::with(['EntireModel', 'Category'])
            ->whereBetween('updated_at', $time)
            ->whereNotIn('status', ['SCRAP'])
            ->orderByDesc('updated_at');
        if (\request()->get("categoryUniqueCode")) $entireInstanceBuilder->where("category_unique_code", \request()->get("categoryUniqueCode"));
        if (\request()->get("entireModel")) $entireInstanceBuilder->where("entire_model_unique_code", \request()->get("entireModelUniqueCode"));

        # 根据自定义时间获取的数据
        $categoryUniqueCode = request()->get('categoryUniqueCode', 'S03');
        $using = EntireInstance::with(['Category'])->whereBetween('updated_at', $time)->where('category_unique_code', $categoryUniqueCode)->whereIn('status', ['INSTALLING', 'INSTALLED'])->count('id');
        $fixed = EntireInstance::with(['Category'])->whereBetween('updated_at', $time)->where('category_unique_code', $categoryUniqueCode)->where('status', 'FIXED')->where('in_warehouse', true)->count('id');
        $returnFactory = EntireInstance::with(['Category'])->whereBetween('updated_at', $time)->where('category_unique_code', $categoryUniqueCode)->where('status', 'RETURN_FACTORY')->count('id');
        $fixing = EntireInstance::with(['Category'])->whereBetween('updated_at', $time)->where('category_unique_code', $categoryUniqueCode)->where('status', 'FIXING')->count('id');
        $total = $using + $fixed + $fixing + $returnFactory;

        $entireInstances = $entireInstanceBuilder->paginate();
//        $entireInstances = $entireInstanceBuilder->get();
        //智能托盘列表页
        $tray = DB::table("tray")->orderBy("id","desc")->get()->toArray();
        return view($this->view())
            ->with("categories", $categories)
            ->with("entireModels", $entireModels)
            ->with("deviceDynamicStatusCurrentMonth", $deviceDynamicStatusCurrentMonth)
            ->with("deviceDynamicStatusNearlyThreeMonth", $deviceDynamicStatusNearlyThreeMonth)
            ->with("using", $using)
            ->with("fixed", $fixed)
            ->with("returnFactory", $returnFactory)
            ->with("fixing", $fixing)
            ->with("total", $total)
            ->with("tray", $tray)
            ->with('entireInstances', $entireInstances);
    }

    public function view($viewName = null)
    {
        $viewName = $viewName ?: \request()->route()->getActionMethod();
        return "Entire.Instance.{$viewName}";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {

        # 如果先选择了设备型号再进入新建页面
        $entireModel = \request()->get('type') ? $entireModel = EntireModel::with(['Category'])->where('unique_code', \request()->get(\request()->get('type')))->firstOrFail() : null;
        $pivotEntireModelAndPartModels = \request()->get('type') ? $partModel = PivotEntireModelAndPartModel::with(['PartModel'])->where('entire_model_unique_code', \request()->get(\request()->get('type')))->get() : null;

        $categories = Category::orderByDesc('id')->pluck('name', 'unique_code');
        $inStatuses = [
            'BUY_IN' => '采购入库',
            'FIXING' => '返修中',
            'FACTORY_RETURN' => '返厂回所',
        ];
        $accounts = Account::orderByDesc('id')->pluck('nickname', 'id');
        return view($this->view())
            ->with('categories', $categories)
            ->with('inStatuses', $inStatuses)
            ->with('entireModel', $entireModel)
            ->with('pivotEntireModelAndPartModels', $pivotEntireModelAndPartModels)
            ->with('accounts', $accounts);
    }

    /**
     * 整件检修入所页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFixing()
    {
        try {
            $entireInstance = EntireInstance::where('identity_code', \request()->get('entireInstanceIdentityCode'))->firstOrFail();
            $accounts = Account::orderByDesc('id')->pluck('nickname', 'id');
            return view($this->view('fixing'))
                ->with('accounts', $accounts)
                ->with('entireInstanceIdentityCode', \request()->get('entireInstanceIdentityCode'))
                ->with('entireInstance', $entireInstance);
        } catch (ModelNotFoundException $exception) {
            return back()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->with('danger', '意外错误');
        }
    }

    /**
     * 整件检修入所
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function postFixing(Request $request)
    {
        try {
            $entireInstance = EntireInstance::where('identity_code', \request()->get('entireInstanceIdentityCode'))->firstOrFail();
            $entireInstance->fill([
                'fix_workflow_serial_number' => null,
                'status' => 'fixing',
                'in_warehouse' => false
            ])
                ->saveOrFail();

            return Response::make('入所成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 智能托盘添加
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        if (\request()->isMethod("post"))
        {
            //从文件中获取传感器当前重量
//            $a = file('../workerman/stdoutFile.txt');
////            dd($a);
//            $b = [];
//            foreach($a as $line => $content){
//                echo 'line '.($line + 1).':'.$content;
//                $b =array($content);
//            }
//            dd($b);
            //把read.txt文本中的内容读取到一个字符串中

//            $str = file_get_contents('../workerman/stdoutFile.txt');
//
////用换行的分割符（\r\n）把字符串分割为数组，也就是把每一行分割为成数组的一个值
//
//            $array = explode("\r\n",$str);
//            for ($i=0;$i<count($array);$i++){
//                $url=$array[$i];
////                dd($url);
//            }
////可以根据自己需要，循环输出从开始行到结束行的内容
////示例：输出文本中第4行内容（因为数组的键值是从0开始的，所以第4行也就是键值3）
//            $d = hexdec(substr($array[$i-4],54,4)); //16进制转为10进制获取重量
//            dd($d);



            $time = time();  //添加托盘的时间戳
            $GrossWeight = 0;  //托盘毛重
            $weight = 0;  //物资重量
            $ResidueWeight = $request->input("max");
            $date = ["GrossWeight"=>$GrossWeight,"weight"=>$weight,"ResidueWeight"=>$ResidueWeight,"tray_code"=>$request->input("tray_code"),"min"=>$request->input("min"),"max"=>$request->input("max"),"precision"=>$request->input("precision"),"place"=>$request->input("place"),"time"=>$time];
            $add = DB::table("tray")->insert($date);
            if ($add){
                return redirect("entire/instance");
            }else{
                return back()->withInput()->with('danger', '数据不存在');
            }

        }
//        try {
//            $newEntireInstanceIdentityCode = WarehouseReport::buyInOnce($request);
//            return back()->with('success', '<h2>入库成功&nbsp;&nbsp;[<a href="' . url('search', $newEntireInstanceIdentityCode) . '">点击查看</a>]</h2>');
//        } catch (ModelNotFoundException $exception) {
//            return back()->withInput()->with('danger', '数据不存在');
//        } catch (\Exception $exception) {
//            return back()->withInput()->with('danger', $exception->getMessage());
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $entireModelUniqueCode
     * @return \Illuminate\Http\Response
     */
    public function show(string $entireModelUniqueCode)
    {
        if (\request()->get("date")) {
            try {
                list($firstDatetime, $lastDatetime) = explode("~", \request()->get("date"));
                list($firstYear, $firstMonth, $firstDay) = explode("-", $firstDatetime);
                $firstTimestamp = mktime(null, null, null, $firstMonth, $firstDay, $firstYear);
                list($lastYear, $lastMonth, $lastDay) = explode("-", $lastDatetime);
                $lastTimestamp = mktime(null, null, null, $lastMonth, $lastDay, $lastYear);
            } catch (\Exception $exception) {
                dd($exception->getMessage());
                return back()->with('error', '时间段不能为空');
            }
        }

        try {
            $entireModel = EntireModel::where('unique_code', $entireModelUniqueCode)->firstOrFail(['name', 'unique_code', 'category_unique_code']);
            $entireInstanceBuilder = EntireInstance::with(["EntireModel", "FixWorkflow"]);
            if (\request()->get("status")) $entireInstanceBuilder->where("status", \request()->get("status"));
            if (\request()->get("date_type") && \request()->get("date")) {
                switch (\request()->get("date_type")) {
                    case "create":
                        $entireInstanceBuilder->whereBetween("created_at", explode("~", \request()->get("date")));
                        break;
                    case "update":
                        $entireInstanceBuilder->whereBetween("updated_at", explode("~", \request()->get("date")));
                        break;
                    case "install":
                        $entireInstanceBuilder->whereBetween("last_installed_time", [$firstTimestamp, $lastTimestamp]);
                        break;
                    case "fix":
                        $entireInstanceBuilder->whereHas("FixWorkflow", function ($fixWorkflow) {
                            $fixWorkflow->whereBetween("created_at", explode("~", \request()->get("date")));
                        });
                        break;
                }
            }

            $entireInstances = $entireInstanceBuilder
                ->where('entire_model_unique_code', $entireModelUniqueCode)
                ->whereNotIn('status', ['SCRAP'])
                ->orderByDesc('updated_at')
                ->paginate();
            Session::put('currentCategoryUniqueCode', $entireModel->category_unique_code);

            return view($this->view())
                ->with('entireInstances', $entireInstances)
                ->with('entireModel', $entireModel);
        } catch (\Exception $exception) {
            return back()->with("error", $exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $identityCode
     * @return \Illuminate\Http\Response
     */
    public function edit($identityCode)
    {
        try {
            $entireInstance = EntireInstance::with([
                'Category',
                'EntireModel',
                'PartInstances',
                'PartInstances.PartModel',
                'FixWorkflow',
            ])
                ->where('identity_code', $identityCode)
                ->firstOrFail();

            return view($this->view())
                ->with('entireInstance', $entireInstance);
        } catch (ModelNotFoundException $exception) {
            return back()->with('danger', $exception->getMessage());
        } catch (\Exception $exception) {
            return back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $identityCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
//            $v = Validator::make($request->all(), EntireInstanceRequest::$RULES, EntireInstanceRequest::$MESSAGES);
//            if ($v->fails()) return Response::make($v->errors()->first(), 422);
//
//            $entireInstance = EntireInstance::where('identity_code', $identityCode)->firstOrFail();
//            $nextFixingData = \App\Facades\EntireInstance::nextFixingTime($entireInstance, $request->get('fix_cycle_value'), $request->get('fix_cycle_unit'));
//            $entireInstance->fill(array_merge($request->all(), $nextFixingData))->saveOrFail();
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
     * 智能托盘删除
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::table("tray")->where("id",$id)->delete();
//            $entireInstance = EntireInstance::where('identity_code', $identityCode)->firstOrFail();
//            $entireInstance->fill(['status' => 'SCRAP'])->saveOrFail();

            return Response::make('删除成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 报废
     * @param $identityCode
     * @return \Illuminate\Http\Response
     */
    public function scrap($identityCode)
    {
        try {
            $entireInstance = EntireInstance::where('identity_code', $identityCode)->firstOrFail();
            $entireInstance->fill(['status' => 'SCRAP'])->saveOrFail();

            return Response::make('报废成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 入所页面
     * @param $entireInstanceIdentityCode
     * @return \Illuminate\Http\Response
     */
    public function getFixingIn($entireInstanceIdentityCode)
    {
        return view($this->view('fixingIn_ajax'))
            ->with('entireInstanceIdentityCode', $entireInstanceIdentityCode)
            ->with('accounts', Account::orderByDesc('id')->pluck('nickname', 'id'));
    }

    /**
     * 入所
     * @param Request $request
     * @param string $entireInstanceIdentityCode
     * @return \Illuminate\Http\Response
     */
    public function postFixingIn(Request $request, string $entireInstanceIdentityCode)
    {
        try {
            # 获取检修单数据
            WarehouseReport::inOnce($request, EntireInstance::where('identity_code', $entireInstanceIdentityCode)->firstOrFail());
            return Response::make('入所成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }
    }

    /**
     * 出库安装页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getInstall()
    {
        try {
            $entireInstance = EntireInstance::where('identity_code', \request()->get('entireInstanceIdentityCode'))->firstOrFail();

            return view($this->view('install_ajax'))
                ->with('entireInstance', $entireInstance);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 出库安装
     * @param Request $request
     * @param string $entireInstanceIdentityCode
     * @return \Illuminate\Http\Response
     */
    public function postInstall(Request $request, string $entireInstanceIdentityCode)
    {
        try {
            $entireInstance = EntireInstance::with(['EntireModel'])->where('identity_code', $entireInstanceIdentityCode)->firstOrFail();
            WarehouseReport::outOnce($request, $entireInstance);

            return Response::make('出库成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }
}
