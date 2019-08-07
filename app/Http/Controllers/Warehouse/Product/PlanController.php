<?php

namespace App\Http\Controllers\Warehouse\Product;

use App\Http\Controllers\Controller;
use App\Model\Account;
use App\Model\WarehouseProductInstance;
use App\Model\WarehouseProductPart;
use App\Model\WarehouseProductPlan;
use App\Model\WarehouseProductPlanProcess;
use App\Model\WarehouseReportProductPart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouseProductInstances = WarehouseProductInstance::with([
            'warehouseProduct',
            'warehouseProduct.category',
        ])->where('status', 'INSTALLED')->paginate();
        return view($this->view('index'), ['warehouseProductInstances' => $warehouseProductInstances]);
    }

    private function view(string $viewName)
    {
        return "Warehouse.Product.Plan.{$viewName}";
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 处理维护排期
     * @param int $warehouseProductPlanId 维护排期编号
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function getProcessWarehouseProductPlan($warehouseProductPlanId)
    {
        try {
            DB::transaction(function () use ($warehouseProductPlanId) {
                # 修改排期时间
                $warehouseProductPlan = WarehouseProductPlan::with(['warehouseProductPart'])->findOrFail($warehouseProductPlanId);
                $warehouseProductPlan->fill([
                    'started_at' => time(),
                    'explain' => date('Y-m-d') . '由' . Account::find(session()->get('account.id'))->nickname . '进行维护',
                    'last_processor_id' => session()->get('account.id'),
                    'last_processed_at' => date('Y-m-d H:i:s')
                ])
                    ->saveOrFail();

                # 减库存
                $warehouseProductPart = WarehouseProductPart::findOrFail($warehouseProductPlan->warehouseProductPart->id);
                $warehouseProductPart->fill(['inventory' => $warehouseProductPart->inventory - 1])->saveOrFail();

                # 添加排期执行记录
                $warehouseProductPlanProcess = new WarehouseProductPlanProcess();
                $warehouseProductPlanProcess->fill([
                    'warehouse_product_plan_id' => $warehouseProductPlanId,
                    'processor_id' => session()->get('account.id'),
                    'processed_at' => date('Y-m-d H:i:s')
                ])->saveOrFail();

                # 登记零件出库记录
                $warehouseReportProductPart = new WarehouseReportProductPart;
                $warehouseReportProductPart->fill([
                    'warehouse_product_part_id' => $warehouseProductPlan->warehouseProductPart->id,
                    'number' => 1,
                    'operation_direction' => 'OUT',
                ])->saveOrFail();
            });


            return Response::make('处理成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }
}
