<?php

namespace App\Facades;

use App\Services\CodeService;
use Illuminate\Support\Facades\Facade;

/**
 * Class Code
 * @method static makeEntireInstanceIdentityCode(string $entireModelUniqueCode): string
 * @method static makeMeasurementIdentityCode(string $partModelUniqueCode, string $entireModelUniqueCode = null): string
 * @method static makePartInstanceIdentityCode(string $partModelUniqueCode, string $entireModeUniqueCode): string
 * @method static makeSerialNumber(string $type): string
 * @method static makeEntireInstanceSerialNumber(string $entireModelUniqueCode): string
 * @package App\Facades
 */
class Code extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CodeService::class;
    }
}
