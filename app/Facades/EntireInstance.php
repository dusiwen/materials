<?php

namespace App\Facades;

use App\Services\EntireInstanceService;
use Illuminate\Support\Facades\Facade;

/**
 * Class EntireInstance
 * @method static incCount(string $entireModelUniqueCode): int
 * @method static incFixedCount(string $entireModelUniqueCode): int
 * @method static nextFixingTime(\App\Model\EntireInstance $entireInstance, int $fixCycleValue = 0, string $fixCycleUnit = 'YEAR'): array
 * @package App\Facades
 */
class EntireInstance extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EntireInstanceService::class;
    }
}
