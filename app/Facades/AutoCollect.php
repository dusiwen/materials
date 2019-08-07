<?php

namespace App\Facades;

use App\Services\AutoCollectService;
use Illuminate\Support\Facades\Facade;

/**
 * Class AutoCollect
 * @method static makeTestData(string $type, string $factoryDeviceCode)
 * @method static makeEntireTestData($entireInstanceFactoryDeviceCode)
 * @method static makePartTestData($partInstanceFactoryDeviceCode)
 * @method static saveTestData(array $testData)
 * @package App\Facades
 */
class AutoCollect extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AutoCollectService::class;
    }
}
