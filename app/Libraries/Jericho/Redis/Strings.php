<?php

namespace Jericho\Redis;

use Illuminate\Support\Facades\Redis;

class Strings extends Basic
{
    private static $_ins = null;

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
     * 获取
     * @param string $key 键名
     * @return mixed|null
     */
    public function getOne(string $key)
    {
        return Redis::command('get', [parent::getBucketName($key)]);
    }

    /**
     * 获取多个
     * @param array $keys
     * @return array|null
     */
    public function getMore(array $keys)
    {
        foreach ($keys as $item) {
            $names[] = $this->getBucketName($item);
        }
        $values = Redis::command('mget', $names);
        if (!$values) return null;
        foreach ($values as $key => $value) {
            $return[$keys[$key]] = $value;
        }
        return $return;
    }

    /**
     * 存储
     * @param string $key 键名
     * @param null $value 键值
     * @param null $expires 过期时间
     * @return mixed
     */
    public function setOne(string $key, $value = null, $expires = null)
    {
        if (!$value) return Redis::command('del', [$this->getBucketName($key)]);
        $value = ((is_array($value) || is_object($value)) ? json_encode($value, 256) : strval($value));
        $expires = $expires ?: env('REDIS_EXPIRES') ?: 3600;
        return Redis::command($expires ? 'setex' : 'set', [$this->getBucketName($key), $expires, $value]);
    }

    /**
     * 设置多个值
     * @param array $map
     * @return mixed
     */
    public function setMore(array $map)
    {
        foreach ($map as $key => $value) {
            $data[] = $this->getBucketName($key);
            $data[] = ((is_array($value) || is_object($value)) ? json_encode($value, 256) : strval($value));
        }
        return Redis::command('mset', $data);
    }

    /**
     * 设置自增长
     * @param string $key 键名
     * @param null $value 自增长值（不写为1）
     * @return mixed
     */
    public function setIncr(string $key, $value = null)
    {
        return Redis::command('incrby', [$this->getBucketName($key), $value ? $value : 1]);
    }

}
