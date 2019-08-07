<?php

namespace App\Facades;

use App\Services\ExcelWriterService;
use Illuminate\Support\Facades\Facade;

/**
 * Class ExcelWriter
 * @method static download(\Closure $closure, string $filename)
 * @package App\Facades
 */
class ExcelWriter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ExcelWriterService::class;
    }
}
