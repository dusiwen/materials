<?php

namespace App\Http\Controllers\Warehouse\Report;

use App\Http\Controllers\Controller;
use App\Model\FixWorkflow;
use App\Model\Maintain;
use App\Model\WarehouseProduct;
use App\Model\WarehouseProductCount;
use App\Model\WarehouseProductInstance;
use App\Model\WarehouseReportProduct;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
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
     * 采购入库页面
     * @param integer $warehouseProductId 整件编号
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBuyIn($warehouseProductId)
    {
        return view('Warehouse.Report.Product.buyIn', ['warehouseProductId' => $warehouseProductId]);
    }

    /**
     * 购买入库
     * @param Request $request
     * @param integer $warehouseProductId 整件编号
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postBuyIn(Request $request, $warehouseProductId)
    {
        try {
            $time = date('Y-m-d H:i:s');
            list($year, $month) = explode('-', date('Y-m'));

            # 读取当年最大数量并自增
            $warehouseProduct = WarehouseProduct::findOrFail($warehouseProductId);
            $warehouseProductCount = WarehouseProductCount::where('year', intval($year))->where('warehouse_product_id', $warehouseProductId)->first();
//            return Response::json($warehouseProductCount);
            $count = !$warehouseProductCount ? 0 : $warehouseProductCount->count;

            $warehouseProductInstanceInsertData = [];
            $warehouseReportProductInsertData = [];
            for ($i = 0; $i < $request->get('number'); $i++) {
                $count += 1;
                $newOpenCode = "{$year}{$month}-{$warehouseProduct->category_open_code}-{$count}";
                $warehouseProductInstanceInsertData[] = [
                    'created_at' => $time,
                    'updated_at' => $time,
                    'warehouse_product_id' => $warehouseProductId,
                    'status' => 'BUY_IN',
                    'open_code' => $newOpenCode,
                    'factory_id' => $request->get('factory_id'),
                    'factory_device_code' => $request->get('factory_device_code')
                ];
                $warehouseReportProductInsertData[] = [
                    'created_at' => $time,
                    'updated_at' => $time,
                    'in_person_id' => $request->get('in_person_id'),
                    'send_person_name' => $request->get('send_person_name', null),
                    'send_person_phone' => $request->get('send_person_phone', null),
                    'in_at' => $request->get('in_at', null),
                    'description' => $request->get('description', null),
                    'in_reason' => $request->get('in_reason'),
                    'warehouse_product_instance_open_code' => $newOpenCode,
                    'operation_direction' => 'IN'
                ];
            }
            DB::table('warehouse_product_instances')->insert($warehouseProductInstanceInsertData);
            DB::table('warehouse_report_products')->insert($warehouseReportProductInsertData);

            # 累计该类别下设备数量
            $warehouseProductCountInsertData = [
                'warehouse_product_id' => $warehouseProductId,
                'year' => intval($year)
            ];
            if (!$warehouseProductCount) $warehouseProductCount = new WarehouseProductCount;
            $warehouseProductCount->fill(array_merge($warehouseProductCountInsertData, ['count' => $count]))->saveOrFail();

            return Response::make('入库成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * 安装出库页面
     * @param string $warehouseProductInstanceOpenCode 设备实例代码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getInstallOut($warehouseProductInstanceOpenCode)
    {
        try {
            $maintains = Maintain::orderByDesc('id')->get();
            return view('Warehouse.Report.Product.installOut', [
                'maintains' => $maintains,
                'warehouseProductInstanceOpenCode' => $warehouseProductInstanceOpenCode,
            ]);
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 安装出库
     * @param Request $request
     * @param string $warehouseProductInstanceOpenCode 设备实例代码
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postInstallOut(Request $request, $warehouseProductInstanceOpenCode)
    {
        try {
            $time = date('Y-m-d');
            DB::transaction(function () use ($request, $time, $warehouseProductInstanceOpenCode) {
                # 修改实例状态及台账编号和是否是正在使用
                $warehouseProductInstance = WarehouseProductInstance::with(['warehouseProduct'])->where('open_code', $warehouseProductInstanceOpenCode)->firstOrFail();
                if ($warehouseProductInstance->flipStatus($warehouseProductInstance->status) != 'BUY_IN') return Response::make('设备状态错误：' . $warehouseProductInstance->flipStatus($warehouseProductInstance->status) . '（' . $warehouseProductInstance->status . '）', 403);
                $warehouseProductInstance->fill([
                    'status' => 'INSTALLED',
                    'maintain_id' => $request->maintain_id,
                    'is_using' => $request->is_using
                ])->saveOrFail();

                # 记录出库行为
                $warehouseReportProduct = new WarehouseReportProduct();
                $warehouseReportProduct->fill(array_merge($request->all(), [
                    'warehouse_product_instance_open_code' => $warehouseProductInstanceOpenCode,
                    'operation_direction' => 'OUT'
                ]))->saveOrFail();

                # 记录该设备下所有零件的维护排期
                $warehouseProductPlanInsertData = [];
                foreach ($warehouseProductInstance->warehouseProduct->warehouseProductParts as $item) {
                    $warehouseProductPlanInsertData[] = [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'warehouse_product_instance_id' => $warehouseProductInstance->warehouseProduct->id,
                        'warehouse_product_part_id' => $item->id,
                        'started_at' => time(),
                        'explain' => $time . '安装设备'
                    ];
                }
                DB::table('warehouse_product_plans')->insert($warehouseProductPlanInsertData);
                DB::table('warehouse_product_plans')->insert([
                    'created_at' => $time,
                    'updated_at' => $time,
                    'started_at' => time(),
                    'warehouse_product_instance_id' => $warehouseProductInstance->warehouseProduct->id,
                    'explain' => '安装设备，初始化报废时间'
                ]);
            });

            return Response::make('出库成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
        }
    }

    /**
     * 返修入库页面
     * @param string $warehouseProductInstanceOpenCode 设备实例代码
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function getFixBySend($warehouseProductInstanceOpenCode)
    {
        return view('Warehouse.Report.Product.fixBySend', ['warehouseProductInstanceOpenCode' => $warehouseProductInstanceOpenCode]);
    }

    /**
     * 返修入库
     * @param Request $request
     * @param string $warehouseProductInstanceOpenCode 设备实例代码
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function postFixBySend(Request $request, $warehouseProductInstanceOpenCode)
    {
        try {
            # 获取设备实例
            $warehouseProductInstance = WarehouseProductInstance::where('open_code', $warehouseProductInstanceOpenCode)->first();
            if ($warehouseProductInstance->flipStatus($warehouseProductInstance->status) != 'INSTALLED') return Response::make('设备状态错误：' . $warehouseProductInstance->flipStatus($warehouseProductInstance->status) . '（' . $warehouseProductInstance->status . '）', 403);

            # 记录入库行为
            $warehouseReportProduct = new WarehouseReportProduct;
            $warehouseReportProduct->fill(array_merge($request->all(), [
                'warehouse_product_instance_open_code' => $warehouseProductInstanceOpenCode,
                'maintain_id' => $warehouseReportProduct->maintain_id,
                'operation_direction' => 'IN'
            ]))->saveOrFail();

            # 添加工单
            $fixWorkflow = new FixWorkflow;
            $fixWorkflow->fill([
                'warehouse_product_instance_open_code' => $warehouseProductInstanceOpenCode,
                'warehouse_report_product_id' => $warehouseReportProduct->id,
                'status' => 'UNFIX',
                'serial_number' => date('YmdHis') . '-' . $warehouseProductInstance->open_code
            ])->saveOrFail();

            # 修改实例状态
            $warehouseProductInstance->fill([
                'status' => 'FIX_BY_SEND',
                'fix_workflow_id' => $fixWorkflow->id,
            ])->saveOrFail();

            return Response::make('返修入库成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 出所送检
     * @param int $fixWorkflowId 工单编号
     * @return \Illuminate\Http\Response
     */
    public function getFixToOut($fixWorkflowId)
    {
        try {
            DB::transaction(function () use ($fixWorkflowId) {
                # 修改工单状态
                $fixWorkflow = FixWorkflow::findOrFail($fixWorkflowId);
                $fixWorkflow->fill(['status' => 'FIX_TO_OUT'])->saveOrFail();

                # 修改设备实例状态
                $warehouseProductInstance = WarehouseProductInstance::where('open_code', $fixWorkflow->warehouse_product_instance_open_code)->firstOrFail();
                $warehouseProductInstance->fill(['status' => 'FIX_TO_OUT'])->saveOrFail();

                # 创建出库记录
                $warehouseReportProduct = new WarehouseReportProduct();
                $warehouseReportProduct->fill([
                    'maintain_id' => $fixWorkflow->maintain_id,
                    'out_person_id' => session()->get('account.id'),
                    'out_reason' => 'FIX_TO_OUT',
                    'out_at' => date('Y-m-d H:i:s'),
                    'warehouse_product_instance_open_code' => $warehouseProductInstance->open_code,
                    'operation_direction' => 'OUT'
                ])->saveOrFail();
            });

            return Response::make('标记完成');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误', 500);
        }
    }

    /**
     * 出所送检返回
     * @param int $fixWorkflowId 工单编号
     * @return \Illuminate\Http\Response
     */
    public function getFixToOutFinish($fixWorkflowId)
    {
        try {
            DB::transaction(function () use ($fixWorkflowId) {
                # 修改工单状态
                $fixWorkflow = FixWorkflow::findOrFail($fixWorkflowId);
                $fixWorkflow->fill(['status' => 'FIX_TO_OUT_FINISH'])->saveOrFail();

                # 修改设备实例状态
                $warehouseProductInstance = WarehouseProductInstance::where('open_code', $fixWorkflow->warehouse_product_instance_open_code)->firstOrFail();
                $warehouseProductInstance->fill(['status' => 'FIX_TO_OUT_FINISH'])->saveOrFail();

                # 创建出库记录
                $warehouseReportProduct = new WarehouseReportProduct();
                $warehouseReportProduct->fill([
                    'maintain_id' => $fixWorkflow->maintain_id,
                    'in_person_id' => session()->get('account.id'),
                    'in_reason' => 'FIX_TO_OUT_FINISH',
                    'in_at' => date('Y-m-d H:i:s'),
                    'warehouse_product_instance_open_code' => $warehouseProductInstance->open_code,
                    'operation_direction' => 'IN'
                ])->saveOrFail();
            });

            return Response::make('标记完成');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误'.$exception->getMessage(), 500);
        }
    }
}
