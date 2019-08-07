<?php

namespace Jericho\Redis;

class Basic
{
    private $_bucketName = null;

    /**
     * 获取容器名称拼接key名称
     * @param string $key 键名
     * @return string
     */
    public function getBucketName(string $key)
    {
        return $this->_bucketName . $key;
    }

    /**
     * 设置容器名称
     * @param string $bucketName 容器名称
     * @param string $sep 分隔符，默认："::"
     */
    public function setBucketName(string $bucketName = null, string $sep = '::')
    {
        $this->_bucketName = $bucketName ? $bucketName . $sep : env('REDIS_BUCKET_NAME', '') . $sep;
    }
}
