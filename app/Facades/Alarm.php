<?php

namespace App\Facades;

use App\Services\AlarmService;
use Illuminate\Support\Facades\Facade;

class Alarm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AlarmService::class;
    }
}
