<?php

namespace App\Facades;

use App\Services\FixWorkflowCycleService;
use Illuminate\Support\Facades\Facade;

/**
 * Class FixWorkflowCycle
 * @method static getBasicInfo(int $year,int $month)
 * @method static getLastMonthFixedCount(int $year, int $month)
 * @method static getCurrentMonthGoingToFixCount(int $year, int $month)
 * @method static getEntireInstanceIdentityCodesForGoingToAutoMakeFixWorkflow(int $year, int $month)
 * @method static autoMakeFixWorkflow(array $entireInstanceIdentityCodes)
 * @package App\Facades
 */
class FixWorkflowCycle extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FixWorkflowCycleService::class;
    }
}
