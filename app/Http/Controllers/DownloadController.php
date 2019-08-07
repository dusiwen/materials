<?php

namespace App\Http\Controllers;

class DownloadController extends Controller
{
    public function in()
    {
        switch (\request()->get('type', null)) {
            case 'single':
                # 单设备入库
                $this->downloadExcel(function ($objPHPExcel) {
                    $objPHPExcel->getActiveSheet()
                        ->setCellValue('A1', '部件出厂代码')
                        ->setCellValue('B1', '部件型号代码');
                    return $objPHPExcel;
                }, '单设备入库模板');
                break;
            case 'more':
                # 多设备入库
                $this->downloadExcel(function ($objPHPExcel) {
                    $objPHPExcel->getActiveSheet()
                        ->setCellValue('A1', '设备类型代码')
                        ->setCellValue('B1', '整件型号')
                        ->setCellValue('C1', '入库类型')
                        ->setCellValue('D1', '供应商名称')
                        ->setCellValue('E1', '整件出厂代码')
                        ->setCellValue('F1', '部件型号')
                        ->setCellValue('G1', '部件出厂代码');
                    return $objPHPExcel;
                }, '批量设备入库模板');
                break;
            default:
                break;
        }
    }

    /**
     * 下载Excel格式文件
     * @param \Closure $closure
     * @param string $filename 文件名
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    private function downloadExcel(\Closure $closure, string $filename)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel = $closure($objPHPExcel);
        header('Content-Type: text/html; charset=utf-8;');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
    }

    public function out()
    {
        switch (request()->get('type', null)) {
            case 'install':
                break;
            case 'installed':
                break;
            default:
                break;
        }
    }
}
