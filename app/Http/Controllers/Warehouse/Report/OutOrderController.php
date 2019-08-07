<?php

namespace App\Http\Controllers\Warehouse\Report;

use App\Http\Controllers\Controller;
use App\Model\Account;
use App\Model\WarehouseProductInstance;
use App\Model\WarehouseReportOutOrder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OutOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouseReportOutOrders = WarehouseReportOutOrder::orderByDesc('id')->paginate();
        return view($this->view('index'))
            ->with('warehouseReportOutOrders', $warehouseReportOutOrders);
    }

    private function view($viewName)
    {
        return "Warehouse.Report.OutOrder.{$viewName}";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # 获取仓库内没有在维修的设备
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
            if (!$request->hasFile('outOrderFile')) return back()->withInput()->with('danger', '上传文件失败');

            $currentTime = date('Y-m-d H:i:s');
            $serialNumber = env('ORGANIZATION_CODE') . date('YmdHis') . '02' . time();  # 01：入库单 02：出库单 03：维修工单

            $uploadFile = $request->file('outOrderFile')->getRealPath();
            $fileType = \PHPExcel_IOFactory::identify($uploadFile);
            $reader = \PHPExcel_IOFactory::createReader($fileType);
            $excel = $reader->load($uploadFile);
            $sheet = $excel->getSheet(0);
            $rows = $sheet->getHighestRow();
            $columns = $sheet->getHighestColumn();
            $warehouseReportOutProductInstances = [];
            $warehouseProductInstanceLogs = [];

            DB::transaction(function () use ($request, $rows, $sheet, $columns, $currentTime, $warehouseReportOutProductInstances, $serialNumber, $warehouseProductInstanceLogs) {
                for ($i = 2; $i <= $rows; $i++) {
                    $rowData = $sheet->rangeToArray('A' . $i . ':' . $columns . $i, NULL, TRUE, FALSE)[0];
                    if (!$rowData[0]) continue;

                    # 修改设备实例状态
                    $type = $request->get('type') != 'INSTALL' ? $request->get('type') : 'INSTALLING';
                    $warehouseProductInstance = WarehouseProductInstance::where('open_code', $rowData[0])->first();
                    if (!$warehouseProductInstance) throw new \Exception('数据不存在：' . $rowData[0]);
//                    switch ($type) {
//                        case 'INSTALL':
//                            switch ($warehouseProductInstance->flipStatus($warehouseProductInstance->status)) {
//                                case 'NONE':
//                                case 'INSTALLING':
//                                case 'INSTALLED':
//                                case 'FIX_TO_OUT':
//                                case 'SCRAP':
//                                    throw new \Exception("状态错误：{$warehouseProductInstance->open_code}（{$warehouseProductInstance->status}）");
//                                    break;
//                            }
//                            break;
//                        case 'FIX_TO_OUT':
//                            switch ($warehouseProductInstance->flipStatus($warehouseProductInstance->status)) {
//                                case 'NONE':
//                                case 'INSTALLING':
//                                case 'INSTALLED':
//                                case 'FIX_TO_OUT':
//                                case 'SCRAP':
//                                    throw new \Exception("状态错误：{$warehouseProductInstance->open_code}（{$warehouseProductInstance->status}）");
//                                    break;
//                            }
//                            break;
//                        case 'SCRAP':
//                            switch ($warehouseProductInstance->flipStatus($warehouseProductInstance->status)) {
//                                case 'NONE':
//                                case 'SCRAP':
//                                    throw new \Exception("状态错误：{$warehouseProductInstance->open_code}（{$warehouseProductInstance->status}）");
//                                    break;
//                            }
//                            break;
//                    }
                    $warehouseProductInstance->fill(['status' => $type])->saveOrFail();

                    # 生成出库设备实例记录
                    $warehouseReportOutProductInstances[] = [
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'warehouse_report_out_order_serial_number' => $serialNumber,
                        'factory_unique_code' => $warehouseProductInstance->factory_unique_code,
                        'warehouse_product_instance_open_code' => $rowData[0],
                        'factory_product_instance_open_code' => $warehouseProductInstance->factory_device_code,
                    ];

                    # 生成设备实例操作日志
                    $warehouseProductInstanceLogs[] = [
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'name' => WarehouseReportOutOrder::$TYPE[$request->get('type')],
                        'description' => '',
                        'operator_id' => $request->get('processor_id'),
                        'warehouse_product_instance_open_code' => $warehouseProductInstance->open_code,
                    ];
                }
                if (!DB::table('warehouse_report_out_product_instances')->insert($warehouseReportOutProductInstances)) throw new \Exception('生成出库单设备实例记录失败');
                if (!DB::table('warehouse_product_instance_logs')->insert($warehouseProductInstanceLogs)) throw new \Exception('生成设备实例日志错误');
            });

            # 生成出库单
            $warehouseReportOutOrder = new WarehouseReportOutOrder;
            $warehouseReportOutOrder->fill([
                'serial_number' => $serialNumber,
                'processed_at' => $currentTime,
                'processor_id' => $request->get('processor_id'),
                'draw_processor_name' => $request->get('draw_processor_name'),
                'draw_processor_phone' => $request->get('draw_processor_phone'),
                'type' => $request->get('type')
            ])->saveOrFail();

            return redirect(url('warehouse/report/outOrder', $warehouseReportOutOrder->id));
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在：' . $exception->getMessage());
        } catch (\Exception $exception) {
            return back()->withInput()->with('danger', $exception->getMessage().$exception->getLine());
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
        try {
            $warehouseReportOutOrder = WarehouseReportOutOrder::with([
                'processor',
                'warehouseReportOutProductInstances',
                'warehouseReportOutProductInstances.warehouseProductInstance',
                'warehouseReportOutProductInstances.warehouseProductInstance.warehouseProduct',
                'warehouseReportOutProductInstances.warehouseProductInstance.factory',
            ])->findOrFail($id);
            $count = [];
            foreach ($warehouseReportOutOrder->warehouseReportOutProductInstances as $warehouseReportOutProductInstance) {
                @$count[$warehouseReportOutProductInstance->warehouseProductInstance->warehouse_product_unique_code] = $count[$warehouseReportOutProductInstance->warehouseProductInstance->warehouse_product_unique_code] + 1;
            }

//        dd($warehouseReportOutOrder->warehouseReportOutProductInstances);
            return view($this->view(\request()->get('type', 'show')))
                ->with('warehouseReportOutOrder', $warehouseReportOutOrder)
                ->with('count', $count);
        } catch (ModelNotFoundException $exception) {
            return back()->withInput()->with('danger', '数据不存在');
        } catch (\Exception $exception) {
            return back()->withInput()->with('danger', '意外错误');
        }
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
        try {
            $warehouseReportOutOrder = WarehouseReportOutOrder::findOrFail($id);
            $warehouseReportOutOrder->delete();
            if (!$warehouseReportOutOrder->trashed()) return Response::make('删除失败', 500);

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
     * 下载出库单模板Excel
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function downloadTemplateExcel()
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()
            ->setCellValue("A1", '设备代码');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="出库单模板.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
    }

    /**
     * 下载出库设备安装模板
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function downloadConfirmTemplateExcel()
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()
            ->setCellValue("A1", '设备代码')
            ->setCellValue("B1", '台账位置代码')
            ->setCellValue('C1', '是否是备用设备')
            ->setCellValue('D1', '安装时间')
            ->setCellValue("A2", '0000_201904_TCA_46')
            ->setCellValue("B2", 'M1')
            ->setCellValue('C2', '是')
            ->setCellValue('D2', '2019-01-01');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="出库设备安装模板.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
    }

    /**
     * 上传确认设备安装台账数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function confirmWarehouseReportOutProductInstance(Request $request)
    {
        try {
            if (!$request->hasFile('confirmWarehouseReportOutProductInstanceFile')) return Response::make('上传文件失败', 404);

            $currentTime = date('Y-m-d H:i:s');

            $uploadFile = $request->file('confirmWarehouseReportOutProductInstanceFile')->getRealPath();
            $fileType = \PHPExcel_IOFactory::identify($uploadFile);
            $reader = \PHPExcel_IOFactory::createReader($fileType);
            $excel = $reader->load($uploadFile);
            $sheet = $excel->getSheet(0);
            $rows = $sheet->getHighestRow();
            $columns = $sheet->getHighestColumn();
            $warehouseProductInstanceLogs = [];

            DB::transaction(function () use ($rows, $sheet, $columns, $currentTime, $request, &$warehouseProductInstanceLogs) {
                for ($i = 2; $i <= $rows; $i++) {
                    $rowData = $sheet->rangeToArray('A' . $i . ':' . $columns . $i, NULL, TRUE, FALSE)[0];
                    if (!$rowData[0]) continue;

                    # 修改设备状态和台账位置
                    $warehouseProductInstance = WarehouseProductInstance::where('open_code', $rowData[0])->first();
                    if (!$warehouseProductInstance) throw new \Exception('数据不存在：' . $rowData[0]);
                    switch ($warehouseProductInstance->flipStatus($warehouseProductInstance->status)) {
                        case 'BUY_IN':
                        case 'INSTALLED':
                        case 'FIX_BY_SEND':
                        case 'FIX_AT_TIME':
                        case 'SCRAP':
                            throw new \Exception("状态错误{$warehouseProductInstance->open_code}（{$warehouseProductInstance->status}）");
                            break;
                        default:
                            break;
                    }
                    $warehouseProductInstance->fill([
                        'status' => 'INSTALLED',
                        'maintain_unique_code' => $rowData[1],
                        'is_using' => $rowData[2] == '是' ? 0 : 1,
                        'installed_at' => $rowData[3]
                    ])->saveOrFail();

                    # 生成设备实例操作日志
                    $warehouseProductInstanceLogs[] = [
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'name' => '记录台账安装位置',
                        'description' => $rowData[1],
                        'operator_id' => $request->get('processor_id'),
                        'warehouse_product_instance_open_code' => $warehouseProductInstance->open_code,
                    ];
                }
                if (!DB::table('warehouse_product_instance_logs')->insert($warehouseProductInstanceLogs)) throw new \Exception('生成设备实例日志错误');
            });

            return Response::make('记录成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make($exception->getMessage(), 500);
        }

    }
}
