<?php

namespace app\Facades;

use Illuminate\Support\Facades\Facade;

class ResponseHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'response-helper-service';
    }
}
