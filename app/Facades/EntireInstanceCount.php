<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class EntireInstanceCount
 * @method static inc(string $entireModelUniqueCode): int
 * @package App\Facades
 */
class EntireInstanceCount extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'entire-instance-count';
    }
}