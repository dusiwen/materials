<?php

namespace App\Facades;

use App\Model\EntireInstance;
use App\Model\FixWorkflow;
use App\Services\WarehouseReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Class WarehouseReport
 * @method static buyInOnce(Request $request): string
 * @method static fixWorkflowOutOnce(Request $request, FixWorkflow $fixWorkflow)
 * @method static returnFactoryOutOnce(Request $request, FixWorkflow $fixWorkflow)
 * @method static factoryReturnInOnce(Request $request, FixWorkflow $fixWorkflow)
 * @method static fixingInOnce(Request $request, EntireInstance $entireInstance)
 * @method static fixWorkflowInOnce(Request $request, FixWorkflow $fixWorkflow)
 * @method static inBatch(Collection $warehouseBatchReports)
 * @method static inOnce(Request $request, EntireInstance $entireInstance)
 * @method static outOnce(Request $request, EntireInstance $entireInstance)
 * @package App\Facades
 */
class WarehouseReport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return WarehouseReportService::class;
    }
}
