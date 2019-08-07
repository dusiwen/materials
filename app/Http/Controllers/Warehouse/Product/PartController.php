<?php

namespace App\Http\Controllers\Warehouse\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WarehouseProductPartRequest;
use App\Model\Category;
use App\Model\PivotFromWarehouseProductToWarehouseProductPart;
use App\Model\WarehouseProductPart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (\request()->ajax()) {
            $warehouseProductPartIds = PivotFromWarehouseProductToWarehouseProductPart::where('warehouse_product_id', \request()->get('warehouseProductId'))->pluck('warehouse_product_part_id')->toArray();
            $warehouseProductParts = WarehouseProductPart::orderByDesc('id')->get()->toArray();
            foreach ($warehouseProductParts as $key => $warehouseProductPart) {
                $warehouseProductParts[$key]['is_checked'] = in_array($warehouseProductPart['id'], $warehouseProductPartIds) ? 'checked' : '';
            }
            return Response::json($warehouseProductParts);
        }
        $warehouseProductParts = WarehouseProductPart::with(['category'])->orderByDesc('id')->paginate();
        return view('Warehouse.Product.Part.index', ['warehouseProductParts' => $warehouseProductParts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderByDesc('id')->pluck('name', 'open_code');
        if (\request()->ajax()) return view('Warehouse.Product.Part.create_ajax')
            ->with('fixCycleTypes', WarehouseProductPart::$FIX_CYCLE_TYPE)
            ->with('categories', $categories);

        return view('Warehouse.Product.Part.create')
            ->with('fixCycleTypes', WarehouseProductPart::$FIX_CYCLE_TYPE)
            ->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            $v = Validator::make($request->all(), WarehouseProductPartRequest::$RULES, WarehouseProductPartRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $warehouseProductPart = new WarehouseProductPart;
            $warehouseProductPart->fill($request->all())->saveOrFail();

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
            $warehouseProductPart = WarehouseProductPart::findOrFail($id);
            $categories = Category::orderByDesc('id')->pluck('name', 'open_code');
            if (\request()->ajax()) return view('Warehouse.Product.Part.edit_ajax')
                ->with('fixCycleTypes', WarehouseProductPart::$FIX_CYCLE_TYPE)
                ->with('warehouseProductPart', $warehouseProductPart)
                ->with('categories', $categories);

            return view('Warehouse.Product.Part.edit')
                ->with('fixCycleTypes', WarehouseProductPart::$FIX_CYCLE_TYPE)
                ->with('warehouseProductPart', $warehouseProductPart)
                ->with('categories', $categories);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $exceptionLine = $exception->getLine();
            $exceptionFile = $exception->getFile();
            // dd("{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
//            return back()->withInput()->with('danger', "{$exceptionMessage}「{$exceptionLine}:{$exceptionFile}」");
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
            $v = Validator::make($request->all(), WarehouseProductPartRequest::$RULES, WarehouseProductPartRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $warehouseProductPart = WarehouseProductPart::findOrFail($id);
            $warehouseProductPart->fill($request->all())->saveOrFail();

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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $warehouseProductVersion = WarehouseProductPart::findOrFail($id);
            $warehouseProductVersion->delete();
            if (!$warehouseProductVersion->trashed()) return Response::make('删除失败', 500);

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
     * 根据整件编号获取零件列表
     * @param $warehouseProductId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function byWarehouseProductId($warehouseProductId)
    {
        try {
            $warehouseProductParts = WarehouseProductPart::whereIn('id', PivotFromWarehouseProductToWarehouseProductPart::where('warehouse_product_id', $warehouseProductId)->pluck('warehouse_product_part_id'))->orderByDesc('id')->get();
            return Response::json($warehouseProductParts);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * 根据设备类型编号获取零件
     * @param int $categoryOpenCode 设备类型编号
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function byCategoryOpenCode($categoryOpenCode)
    {
        try {
            $warehouseProductParts = WarehouseProductPart::where('category_open_code', $categoryOpenCode)->get();
            return Response::json($warehouseProductParts);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }
}
