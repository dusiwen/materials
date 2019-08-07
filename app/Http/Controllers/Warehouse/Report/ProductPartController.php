<?php

namespace App\Http\Controllers\Warehouse\Report;

use App\Http\Controllers\Controller;
use App\Model\WarehouseProcurementPart;
use App\Model\WarehouseProcurementPartInstance;
use App\Model\WarehouseReportProductPart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ProductPartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouseProcurementPart = WarehouseProcurementPart::with([
            'processor',
            'warehouseProcurementPartInstances',
            'warehouseProcurementPartInstances.warehouseProductPart'
        ])
            ->findOrFail(\request()->get('warehouseProcurementPartId'));

        return view($this->view('create'), ['warehouseProcurementPart' => $warehouseProcurementPart]);
    }

    private function view($viewName)
    {
        return "Warehouse.Report.ProductPart.{$viewName}";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $time = date('Y-m-d H:i:s');
        $insertData = [];
        foreach ($request->except('in_at', 'send_person_name', 'send_person_phone', 'warehouseProcurementPartId') as $key => $value) {
            # 更新库存
            DB::table('warehouse_product_parts')->where('id', $key)->update([
                'inventory' => DB::table('warehouse_product_parts')->where('id', $key)->select('inventory')->first()->inventory + $value
            ]);

            # 写入零件入库记录
            $insertData[] = [
                'created_at' => $time,
                'updated_at' => $time,
                'warehouse_procurement_part_id' => $request->get('warehouseProcurementPartId'),
                'warehouse_product_part_id' => $key,
                'number' => $value,
                'send_person_name' => $request->get('send_person_name'),
                'send_person_phone' => $request->get('send_person_phone'),
                'in_at' => $request->get('in_at'),
                'in_person_id' => session()->get('account.id'),
                'operation_direction' => 'IN',
            ];
        }
        if (!$insertData) return Response::make('入库数量为空', 403);
        if (!DB::table('warehouse_report_product_parts')->insert($insertData)) return Response::make('保存失败', 500);

        return Response::make('保存成功');
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
        $warehouseReportProductPart = WarehouseReportProductPart::with(['warehouseProductPart'])->findOrFail($id);
        return view($this->view('edit'), ['warehouseReportProductPart' => $warehouseReportProductPart]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $warehouseReportProductPart = WarehouseReportProductPart::findOrFail($id);
            $warehouseReportProductPart->fill($request->all())->saveOrFail();

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
