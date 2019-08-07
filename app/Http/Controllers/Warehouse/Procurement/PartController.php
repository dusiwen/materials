<?php

namespace App\Http\Controllers\Warehouse\Procurement;

use App\Http\Controllers\Controller;
use App\Model\WarehouseProcurementPart;
use App\Model\WarehouseProductPart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouseProcurementParts = WarehouseProcurementPart::with(['processor'])->orderByDesc('id')->paginate();
        return view($this->view('index'), ['warehouseProcurementParts' => $warehouseProcurementParts]);
    }

    private function view($viewName)
    {
        return "Warehouse.Procurement.Part.{$viewName}";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->view('create'));
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
            if (!$request->hasFile('file')) return Response::make('上传文件失败', 404);

            $time = date('Y-m-d H:i:s');

            # 生成零件采购单
            $warehouseProcurementPart = new WarehouseProcurementPart;
            $warehouseProcurementPart->fill([
                'created_at' => $time,
                'updated_at' => $time,
                'serial_number' => env('ORGANIZATION_CODE').date('YmdHis') . '04' . time(),  # 01：入库单 02：出库单 03：维修工单 04：零件采购单
                'processor_id' => session()->get('account.id'),
                'processed_at' => date('Y-m-d')
            ])->saveOrFail();

            # 加载文件内容
            $uploadFile = $request->file('file')->getRealPath();
            $fileType = \PHPExcel_IOFactory::identify($uploadFile);
            $reader = \PHPExcel_IOFactory::createReader($fileType);
            $excel = $reader->load($uploadFile);
            $sheet = $excel->getSheet(0);
            $rows = $sheet->getHighestRow();
            $columns = $sheet->getHighestColumn();
            $insertData = [];
            for ($i = 2; $i <= $rows; $i++) {
                $rowData = $sheet->rangeToArray('A' . $i . ':' . $columns . $i, NULL, TRUE, FALSE)[0];
                if ($rowData[2] > 0) {
                    $insertData[] = [
                        'created_at' => $time,
                        'updated_at' => $time,
                        'warehouse_procurement_part_id' => $warehouseProcurementPart->id,
                        'warehouse_product_part_id' => $rowData[0],
                        'number' => $rowData[2]
                    ];
                }
            }
            if (!$insertData) {
                $warehouseProcurementPart->delete();
                return Response::make('批量添加内容为空', 404);
            }

            # 生成零件采购单实例记录
            if (!DB::table('warehouse_procurement_part_instances')->insert($insertData)) {
                $warehouseProcurementPart->delete();
                return Response::make('保存到数据库失败', 500);
            }

            return Response::make('上传成功');
        } catch (ModelNotFoundException $exception) {
            return Response::make('数据不存在', 404);
        } catch (\Exception $exception) {
            return Response::make('意外错误' . $exception->getMessage(), 500);
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
            $warehouseProcurementPart = WarehouseProcurementPart::with([
                'processor',
                'warehouseProcurementPartInstances',
                'warehouseProcurementPartInstances.warehouseProductPart',
                'warehouseReportProductParts',
                'warehouseReportProductParts.warehouseProductPart',
                'warehouseReportProductParts.inPerson',
            ])->findOrFail($id);
            return view($this->view('show'), ['warehouseProcurementPart' => $warehouseProcurementPart]);
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warehouseProcurementPart = WarehouseProcurementPart::with([
            'processor',
            'warehouseProcurementPartInstances',
            'warehouseProcurementPartInstances.warehouseProductPart'
        ])
            ->findOrFail($id);
        return view($this->view('edit'), ['warehouseProcurementPart' => $warehouseProcurementPart]);
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

    public function downloadProcurementPartTemplateExcel()
    {
        $warehouseProductParts = WarehouseProductPart::all();
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()
            ->setCellValue("A1", '编号')
            ->setCellValue("B1", '名称')
            ->setCellValue("C1", '数量');
        foreach ($warehouseProductParts as $key => $warehouseProductPart) {
            $index = $key + 2;
            $objPHPExcel->getActiveSheet()
                ->setCellValue('A' . $index, $warehouseProductPart->id)
                ->setCellValue('B' . $index, $warehouseProductPart->name)
                ->setCellValue('C' . $index, 0);
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="零件采购单模板.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
    }
}
