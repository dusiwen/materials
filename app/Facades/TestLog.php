<?php
namespace App\Facades;
use App\Services\TestLogService;
use Illuminate\Support\Facades\Facade;

class TestLog extends Facade{
    protected static function getFacadeAccessor()
    {
        return TestLogService::class;
    }
}
