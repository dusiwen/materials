<?php

namespace App\Http\Controllers\Warehouse\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WarehouseProductRequest;
use App\Model\Category;
use App\Model\PivotFromWarehouseProductToWarehouseProductPart;
use App\Model\WarehouseProduct;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (\request()->ajax()) return Response::json(WarehouseProduct::with(['category'])->orderByDesc('id')->get());
        $warehouseProducts = WarehouseProduct::with(['warehouseProductParts', 'category'])->orderByDesc('id')->paginate();
        return view('Warehouse.Product.Post.index', ['warehouseProducts' => $warehouseProducts]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderByDesc('id')->pluck('name', 'open_code');
        if (\request()->ajax()) return view('Warehouse.Product.Post.create_ajax', ['categories' => $categories]);
        return view('Warehouse.Product.Post.create', [
            'categories' => $categories,
        ]);
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
            $v = Validator::make($request->all(), WarehouseProductRequest::$RULES, WarehouseProductRequest::$MESSAGES);
            if ($v->fails()) return Response::make($v->errors()->first(), 422);

            $warehouseProduct = new WarehouseProduct;
            $warehouseProduct->fill(array_merge($request->all(), ['organization_code' => env('ORGANIZATION_CODE')]))->saveOrFail();

            $time = date('Y-m-d H:i:s');
            if ($request->has('warehouse_product_part_ids')) {
                $warehouseProductPartIds = array_sort($request->get('warehouse_product_part_ids'));
                foreach ($warehouseProductPartIds as $item) {
                    $insertData[] = [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'warehouse_product_id' => $warehouseProduct->id,
                        'warehouse_product_part_id' => $item
                    ];
                }
                if (!DB::table('pivot_from_warehouse_product_to_warehouse_product_parts')->insert($insertData)) return Response::make('绑定零件失败', 500);
            }

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
     * @param string $uniqueCode
     * @return \Illuminate\Http\Response
     */
    public function edit($uniqueCode)
    {
        try {
            $warehouseProduct = WarehouseProduct::where('unique_code',$uniqueCode)->firstOrFail();
            $categories = Category::orderByDesc('id')->pluck('name', 'open_code');
            $warehouseProductPartIds = PivotFromWarehouseProductToWarehouseProductPart::where('warehouse_product_id', $uniqueCode)->pluck('warehouse_product_part_id');

            return view('Warehouse.Product.Post.edit')
                ->with('warehouseProduct', $warehouseProduct)
                ->with('warehouseProductPartIds', $warehouseProductPartIds)
                ->with('categories', $categories);
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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $v = Validator::make($request->all(), WarehouseProductRequest::$RULES, WarehouseProductRequest::$MESSAGES);
            if ($v->fails()) return back()->withInput()->with('danger', $v->errors()->first());

            $warehouseProduct = WarehouseProduct::findOrFail($id);
            $warehouseProduct->fill($request->all())->saveOrFail();

            DB::table('pivot_from_warehouse_product_to_warehouse_product_parts')->where('warehouse_product_id', $id)->delete();
            if ($request->has('warehouse_product_part_ids')) {
                $warehouseProductPartIds = array_sort($request->get('warehouse_product_part_ids'));
                foreach ($warehouseProductPartIds as $item) {
                    $insertData[] = [
                        'updated_at' => date('Y-m-d H:i:s'),
                        'warehouse_product_id' => $id,
                        'warehouse_product_part_id' => $item
                    ];
                }
                if (!DB::table('pivot_from_warehouse_product_to_warehouse_product_parts')->insert($insertData)) return Response::make('绑定零件失败', 500);
            }

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
            $warehouseProduct = WarehouseProduct::findOrFail($id);
            $warehouseProduct->delete();
            if (!$warehouseProduct->trashed()) return Response::make('删除失败', 500);

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
