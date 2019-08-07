<?php

namespace App\Services;

use App\Facades\EntireInstance;
use App\Model\EntireModel;

class CodeService
{
    private $_currentDatetime;
    private $_currentYearMonth;
    private $_currentTime;
    private $_serialNumberType = [
        'IN' => '01',  # 入库单
        'OUT' => '02',  # 出库单
        'FIX_WORKFLOW' => '03',  # 工单
        'FIX_WORKFLOW_PROCESS' => '04',  # 检测单
        'FIX_WORKFLOW_PROCESS_PART' => '05',  # 部件检测单
        'FIX_WORKFLOW_PROCESS_ENTIRE' => '06',  # 整件检测单
    ];

    public function __construct()
    {
        $this->_currentDatetime = date('YmdHis');
        $this->_currentYearMonth = date('Ym');
        $this->_currentTime = time();
    }

    /**
     * 生成整件身份码
     * @param string $entireModelUniqueCode
     * @return string
     */
    public function makeEntireInstanceIdentityCode(string $entireModelUniqueCode)
    {
        $entireModel = EntireModel::with([
            'Category',
            'Category.Race',
        ])
            ->where('unique_code', $entireModelUniqueCode)
            ->firstOrFail();

        return "{$entireModel->category_unique_code}{$entireModel->unique_code}"
            . env('ORGANIZATION_CODE')
            . str_pad(
                EntireInstance::incCount($entireModelUniqueCode),
                $entireModel->Category->Race->serial_number_length,
                '0',
                STR_PAD_LEFT
            );
    }

    /**
     * 生成部件身份码
     * @param string $partModelUniqueCode
     * @param string $entireModeUniqueCode
     * @return string
     */
    public function makePartInstanceIdentityCode(string $partModelUniqueCode, string $entireModeUniqueCode): string
    {
        return env('ORGANIZATION_CODE') . "PI{$this->_currentDatetime}{$entireModeUniqueCode}{$partModelUniqueCode}{$this->_currentTime}";
    }

    /**
     * 生成新的整件设备流水号
     * @param string $entireModelUniqueCode
     * @return string
     */
    public function makeEntireInstanceSerialNumber(string $entireModelUniqueCode): string
    {
        $entireInstanceCount = EntireInstance::incFixedCount($entireModelUniqueCode);
        $entireInstanceCount = str_pad($entireInstanceCount, 5, '0', STR_PAD_LEFT);
        return env('ORGANIZATION_CODE') . "{$this->_currentYearMonth}{$entireModelUniqueCode}{$entireInstanceCount}";
    }

    /**
     * 生成流水单号
     * @param string $type
     * @return string
     */
    public function makeSerialNumber(string $type): string
    {
        return env('ORGANIZATION_CODE') . "{$this->_currentDatetime}_{$this->_serialNumberType[$type]}_{$this->_currentTime}";
    }


    /**
     * 生成测试模板身份码
     * @param string $entireModelUniqueCode
     * @param string|null $partModelUniqueCode
     * @return string
     */
    public function makeMeasurementIdentityCode(string $entireModelUniqueCode, string $partModelUniqueCode = null): string
    {
        $header = $partModelUniqueCode ? 'MP' : 'ME';
        $uniqueCode = $partModelUniqueCode ? $partModelUniqueCode : $entireModelUniqueCode;
        return env('ORGANIZATION_CODE') . "{$header}{$this->_currentDatetime}{$uniqueCode}{$this->_currentTime}";
    }
}
