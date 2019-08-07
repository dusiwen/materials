<?php

namespace App\Http\Controllers\Warehouse\Report;

use App\Http\Controllers\Controller;
use App\Model\Account;
use App\Model\FixWorkflow;
use App\Model\WarehouseProduct;
use App\Model\WarehouseProductInstance;
use App\Model\WarehouseReportInOrder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jericho\Redis\Hashs;

class InOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouseReportInOrders = WarehouseReportInOrder::orderByDesc('id')->paginate();
        return view($this->view('index'))
            ->with('warehouseReportInOrders', $warehouseReportInOrders);
    }

    public function view($viewName)
    {
        return "Warehouse.Report.InOrder.{$viewName}";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::orderByDesc('id')->pluck('nickname', 'id');
        return view($this->view('create'))
            ->with('accounts', $accounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            if (!$request->hasFile('inOrderFile')) return back()->withInput()->with('danger', '上传文件失败');

            $currentTime = date('Y-m-d H:i:s');
            $serialNumber = env('ORGANIZATION_CODE') . date('YmdHis') . '01' . time();  # 01：入库单 02：出库单 03：维修工单
            list($year, $month) = explode('-', date('Y-m'));

            $uploadFile = $request->file('inOrderFile')->getRealPath();
            $fileType = \PHPExcel_IOFactory::identify($uploadFile);
            $reader = \PHPExcel_IOFactory::createReader($fileType);
            $excel = $reader->load($uploadFile);
            $sheet = $excel->getSheet(0);
            $rows = $sheet->getHighestRow();
            $columns = $sheet->getHighestColumn();
            $warehouseReportInProductInstances = [];
            $fixWorkflows = [];
            $warehouseProductInstanceLogs = [];

            DB::transaction(function () use ($request, $rows, $sheet, $columns, $year, $month, $currentTime, $warehouseReportInProductInstances, $serialNumber, $fixWorkflows, $warehouseProductInstanceLogs) {
                for ($i = 2; $i <= $rows; $i++) {
                    $rowData = $sheet->rangeToArray('A' . $i . ':' . $columns . $i, NULL, TRUE, FALSE)[0];

                    if ($request->get('type') == 'BUY_IN') {
                        # 查询工厂设备代码是否重复
                        $warehouseProductInstance = WarehouseProductInstance::where('factory_device_code', $rowData[1])->first();
                        if ($warehouseProductInstance) throw new \Exception("工厂设备代码重复：{$rowData[1]}");

                        # 读取当年最大数量并自增
                        $warehouseProduct = WarehouseProduct::where('unique_code', $rowData[2])->firstOrFail();
                        $warehouseProductCount = Hashs::ins('maintain')->setIncr('warehouse_product_count', $rowData[2]);

                        # 生成设备编码
                        $organizationCode = env('ORGANIZATION_CODE');
                        $warehouseProductCount = str_pad($warehouseProductCount, 5, "0", STR_PAD_LEFT);
                        $currentWarehouseProductOpenCode = $newWarehouseProductOpenCode = "{$organizationCode}_{$year}{$month}_{$warehouseProduct->category_open_code}_{$warehouseProductCount}";

                        # 生成设备实例
                        $warehouseProductInstance = new WarehouseProductInstance;
                        $warehouseProductInstance->fill([
                            'created_at' => $currentTime,
                            'updated_at' => $currentTime,
                            'warehouse_product_unique_code' => $rowData[2],
                            'status' => $request->get('type'),
                            'open_code' => $newWarehouseProductOpenCode,
                            'factory_unique_code' => $rowData[0],
                            'factory_device_code' => $rowData[1]
                        ])->saveOrFail();
                    } else {
                        # 获取设备实例
                        $warehouseProductInstance = WarehouseProductInstance::where('factory_device_code', $rowData[1])->firstOrFail();
                        # 修改设备实例
                        $warehouseProductInstance->fill(['status' => $request->get('type')])->saveOrFail();
                        $currentWarehouseProductOpenCode = $warehouseProductInstance->open_code;
                    }

                    # 生成设备实例操作日志
                    $warehouseProductInstanceLogs[] = [
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'name' => WarehouseReportInOrder::$TYPE[$request->get('type')],
                        'description' => '',
                        'operator_id' => $request->get('processor_id'),
                        'warehouse_product_instance_open_code' => $warehouseProductInstance->open_code,
                    ];

                    # 生成入库设备实例
                    $warehouseReportInProductInstances[] = [
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'warehouse_report_in_order_serial_number' => $serialNumber,
                        'warehouse_product_instance_open_code' => $warehouseProductInstance->open_code,
                        'factory_unique_code' => $rowData[0],
                        'factory_product_instance_open_code' => $rowData[1],
                    ];

                    # 生成测试工单
                    $fixWorkflowCount = Hashs::ins('maintain')->setIncr('fix_workflow_count', date('Y'));
                    $fixWorkflow = new FixWorkflow;
                    $fixWorkflow->fill([
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'warehouse_product_instance_open_code' => $currentWarehouseProductOpenCode,
                        'warehouse_report_in_order_serial_number' => $serialNumber,
                        'status' => 'UNFIX',
                        'processor_id' => $request->get('processor_id'),
                        'serial_number' => env('ORGANIZATION_CODE') . date('Ymd') . '03' . str_pad($fixWorkflowCount, 7, "0", STR_PAD_LEFT),
                        'description' => WarehouseReportInOrder::$TYPE[$request->get('type')],
                    ])
                        ->saveOrFail();
                    # 保存工单与设备实例关系
                    $warehouseProductInstance->fill(['fix_workflow_id' => $fixWorkflow->id])->saveOrFail();
                }
                if (!DB::table('warehouse_report_in_product_instances')->insert($warehouseReportInProductInstances)) throw new \Exception('生成入库单设备实例记录失败');
                if (!DB::table('fix_workflows')->insert($fixWorkflows)) throw new \Exception('生成工单错误');
                if (!DB::table('warehouse_product_instance_logs')->insert($warehouseProductInstanceLogs)) throw new \Exception('生成设备实例日志错误');
            });

            # 生成入库单
            $warehouseReportInOrder = new WarehouseReportInOrder;
            $warehouseReportInOrder->fill([
                'serial_number' => $serialNumber,
                'processed_at' => $currentTime,
                'processor_id' => $request->get('processor_id'),
                'send_processor_name' => $request->get('send_processor_name'),
                'send_processor_phone' => $request->get('send_processor_phone'),
                'type' => $request->get('type')
            ])->saveOrFail();


            return redirect(url('warehouse/report/inOrder', $warehouseReportInOrder->id));
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在' . $exception->getMessage());
        } catch (\Exception $exception) {
            return back()->withInput()->with('danger', $exception->getMessage());
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
        $warehouseReportInOrder = WarehouseReportInOrder::with([
            'processor',
            'warehouseReportInProductInstances',
            'warehouseReportInProductInstances.warehouseProductInstance',
            'warehouseReportInProductInstances.warehouseProductInstance.warehouseProduct',
            'warehouseReportInProductInstances.warehouseProductInstance.factory',
        ])
            ->findOrFail($id);
        $count = [];
        foreach ($warehouseReportInOrder->warehouseReportInProductInstances as $warehouseReportInProductInstance) {
            @$count[$warehouseReportInProductInstance->warehouseProductInstance->warehouse_product_unique_code] = $count[$warehouseReportInProductInstance->warehouseProductInstance->warehouse_product_unique_code] + 1;
        }
        return view($this->view(\request()->get('type', 'show')))
            ->with('warehouseReportInOrder', $warehouseReportInOrder)
            ->with('count', $count);

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
     * 下载入库单模板
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function downloadTemplateExcel()
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()
            ->setCellValue("A1", '供应商统一代码')
            ->setCellValue("B1", '供应商设备编码')
            ->setCellValue("C1", '设备类型统一代码');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="入库单模板.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
    }
}
