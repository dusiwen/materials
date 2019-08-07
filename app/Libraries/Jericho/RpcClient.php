<?php

namespace Jericho;

use Hprose\Http\Client;

class RpcClient
{
    public $url = null;
    public $client = null;
    private static $_ins = null;

    private function __construct()
    {
        $this->url = env('HPROSE_URL_PASSPORT');
    }

    public static function ins()
    {
        if(!self::$_ins) self::$_ins = new self;
        return self::$_ins;
    }

    /**
     * 同步请求
     * @param string $version 版本
     * @return Client|null
     */
    public function sync($version = 'v1')
    {
        if (!$this->client) $this->client = new Client($this->url . '/' . $version, false);
        return $this->client;
    }

    /**
     * 异步请求
     * @param string $version
     * @return Client|null
     */
    public function async($version = 'v1')
    {
        if (!$this->client) $this->client = new Client($this->url . '/' . $version);
        return $this->client;
    }
}