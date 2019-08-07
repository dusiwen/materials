<?php

namespace Jericho\Redis;

use Illuminate\Support\Facades\Redis;

class SortedSets extends Basic
{
    private static $_ins = null;
    private static $_start = 1;
    private static $_end = 10;

    private function __construct($bucketName = null)
    {
        parent::setBucketName($bucketName);
    }

    public static function ins($bucketName = null)
    {
        if (!self::$_ins) self::$_ins = new self($bucketName);
        return self::$_ins;
    }
    /**
     * 设置单个值
     * @param string $key
     * @param $score
     * @param $value
     * @return bool
     */
    public function setOne(string $key, $score, $value)
    {
        return Redis::command('zadd', [$this->getBucketName($key), $score, $value]) ? true : false;
    }

    /**
     * 设置多个值
     * @param string $key
     * @param $map
     * @return mixed
     */
    public function setMore(string $key, $map)
    {
        $value = [$this->getBucketName($key)];
        foreach ($map as $k => $v) {
            $value[] = $k;
            $value[] = $v;
        }

        return Redis::command('zadd', $value);
    }

    /**
     * @param int $page
     * @param int $prePage
     * @return mixed
     */
    public function paginate($page = 1, $prePage = 10)
    {
        self::$_start = ($page - 1) * $prePage;
        self::$_end = $page * $prePage;
        return self;
    }

    public function getOne(string $key, bool $desc = true, int $start = 0, int $end = 0)
    {
        return Redis::command($desc?'zrevrange':'zrange',[$key, $start < $end ?: self::$_start, $end > $start ?: self::$_end]);
    }
}
