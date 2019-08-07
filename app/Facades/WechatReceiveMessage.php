<?php

namespace App\Facades;

use App\Services\WechatReceiveMessageService;
use Illuminate\Support\Facades\Facade;

class WechatReceiveMessage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return WechatReceiveMessageService::class;
    }
}
