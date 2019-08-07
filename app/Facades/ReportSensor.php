<?php
namespace App\Facades;
use App\Services\ReportSensorService;
use Illuminate\Support\Facades\Facade;

class ReportSensor extends Facade{
    protected static function getFacadeAccessor()
    {
        return ReportSensorService::class;
    }
}
