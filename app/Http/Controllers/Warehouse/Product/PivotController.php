<?php

namespace App\Http\Controllers\Warehouse\Product;

use App\Http\Controllers\Controller;
use App\Model\PivotFromWarehouseProductToWarehouseProductPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PivotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\request()->ajax()) {
            $pivots = PivotFromWarehouseProductToWarehouseProductPart::with(['warehouseProductPart'])->where('warehouse_product_id', \request()->get('warehouseProductId'))->get();
            return view('Warehouse.Product.Pivot.edit_ajax', [
                'warehouseProductId' => \request()->get('warehouseProductId'),
                'pivots' => $pivots
            ]);
        }
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $warehouseProductId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $warehouseProductId)
    {
        foreach ($request->all() as $key => $value) {
            $id = explode('_', $key)[1];
            DB::table('pivot_from_warehouse_product_to_warehouse_product_parts')->where('id', $id)->update(['number' => $value]);
        }
        return Response::make('编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
