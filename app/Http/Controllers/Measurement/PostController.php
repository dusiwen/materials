<?php

namespace App\Http\Controllers\Measurement;

use App\Facades\Code;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MeasurementRequest;
use App\Model\EntireModel;
use App\Model\Measurement;
use App\Model\PivotFromWarehouseProductToWarehouseProductPart;
use App\Model\project;
use App\Model\WarehouseProduct;
use App\Model\WarehouseProductPart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        if (\request()->has('entireModelUniqueCode')) {
//            $measurements = Measurement::with(['EntireModel', 'EntireModel.Category', 'PartModel'])->
//            orderByDesc('id')
//                ->where('entire_model_unique_code', \request()->get('entireModelUniqueCode'))
//                ->paginate();
//            $type = 'product';
//        } else {
//            $measurements = Measurement::with(['EntireModel', 'EntireModel.Category', 'PartModel'])
//                ->orderByDesc('id')
//                ->paginate();
//            $type = 'self';
//        }
//        $project = project::where([])->orderBy('id','desc')->get()->toArray();//获取所有项目(分页未写)orm
        $project = DB::table("project")->orderByDesc("id")->get()->toArray();
        $i= DB::table("project")->count();
        return view('Measurement.Post.index')
            ->with('entireModelUniqueCode', \request()->get('entireModelUniqueCode'))
//            ->with('measurements', $measurements)
            ->with('project', $project)
            ->with('i', $i);
//            ->with('type', $type);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            if (\request()->ajax()) {
                // 读取整件和零件数据
                switch (\request()->get('type')) {
                    case 'product':
                        $warehouseProduct = WarehouseProduct::where('id', \request()->get('warehouseProductId'))->firstOrFail();
                        $warehouseProductParts = WarehouseProductPart::whereIn('id', PivotFromWarehouseProductToWarehouseProductPart::where('warehouse_product_id', $warehouseProduct->id)->pluck('warehouse_product_part_id')->toArray())->get();
                        return view('Measurement.Post.create_ajax_product', ['warehouseProduct' => $warehouseProduct, 'warehouseProductParts' => $warehouseProductParts]);
                        break;
                    case 'part':
                        return Response::make('类型错误', 500);
                        break;
                    case 'self':
                        break;
                    default:
                        return Response::make('类型错误', 500);
                        break;
                }
            } else {
                switch (\request()->get('type')) {
                    case 'product':
                        $warehouseProduct = WarehouseProduct::where('id', \request()->get('warehouseProductId'))->firstOrFail();
                        $warehouseProductParts = WarehouseProductPart::whereIn('id', PivotFromWarehouseProductToWarehouseProductPart::where('warehouse_product_id', $warehouseProduct->id)->pluck('warehouse_product_part_id')->toArray())->get();
                        return view('Measurement.Post.create_product', ['warehouseProduct' => $warehouseProduct, 'warehouseProductParts' => $warehouseProductParts]);
                        break;
                    case 'part':
                        break;
                    case 'self':
                        $entireModels = EntireModel::orderByDesc('id')->pluck('name', 'unique_code');
                        return view('Measurement.Post.create_self')
                            ->with('entireModels', $entireModels);
                        break;
                    default:
                        $entireModels = EntireModel::orderByDesc('id')->pluck('name', 'unique_code');
                        return view('Measurement.Post.create_self')
                            ->with('entireModels', $entireModels);
                        break;
                        break;
                }
            }
            return Response::make();
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * 新建项目
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
//            $v = Validator::make($request->all(), MeasurementRequest::$RULES, MeasurementRequest::$MESSAGES);
//            if ($v->fails()) return Response::make($v->errors()->first(), 422);
//
//            # 生成身份码
//            $identityCode = Code::makeMeasurementIdentityCode($request->get('entire_model_unique_code'), $request->get('part_model_unique_code'));
//            $measurement = new Measurement;
//            $measurement->fill(array_merge($request->all(), ['identity_code' => $identityCode]))->saveOrFail();

            $time = time();
            //随机生成22位WBS元素
            $returnStr='';
            $pattern = '1234567890ABCDEFGHIJKLOMNOPQRSTUVWXYZ';
            for($i = 0; $i < 22; $i ++) {
                $returnStr .= $pattern {mt_rand ( 0, 36 )}; //生成php随机数
            }
            $WBS = $returnStr;
//            return Response::make($request->get("remarks"), 404);
            DB::table("project")->insert(["date"=>$request->get("date"),"project_name"=>$request->get("projectName"),"WBS"=>$WBS,"time"=>$time]);
            return Response::make('新建成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            // return back()->withInput()->with('danger',"{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
            return Response::make('意外错误' . $exceptionMessage, 500);
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $measurement = Measurement::findOrFail($id);
            $entireModels = EntireModel::orderByDesc('id')->pluck('name', 'unique_code');
            return view('Measurement.Post.edit')
                ->with('measurement', $measurement)
                ->with('entireModels', $entireModels);
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $v = Validator::make($request->all(), MeasurementRequest::$RULES, MeasurementRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $measurement = Measurement::findOrFail($id);
            $measurement->fill($request->all())->saveOrFail();

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
     * 删除对应项目
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::table("project")->where("id",$id)->delete();

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
