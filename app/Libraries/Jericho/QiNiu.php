<?php

namespace Jericho;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiNiu
{
    private static $_instance = null;
    private $_access_key = null;
    private $_secret_key = null;
    private $_prefix_url = null;
    private $_expires = null;
    private $_bucket_name = null;

    private function __construct($access_key = null, $secret_key = null, $bucket_name = null)
    {
        $this->_access_key = env('QINIU_ACCESS_KEY');
        $this->_secret_key = env('QINIU_SECRET_KEY');
        $this->_prefix_url = env('QINIU_PREFIX_URL');
        $this->_expires = env('QINIU_EXPIRES');
        $this->_bucket_name = env('QINIU_BUCKET_NAME');
    }

    public static function ins($access_key = null, $secret_key = null, $bucket_name = null)
    {
        if (!self::$_instance)
            self::$_instance = new self($access_key, $secret_key, $bucket_name);
        return self::$_instance;
    }

    /**
     * 设置URL前缀
     * @param string $prefix_url URL前缀
     * @return mixed
     */
    public function prefixUrl($prefix_url = null)
    {
        $this->_prefix_url = $prefix_url ? $prefix_url : config('qiniu.prefix_url');
        return $this;
    }

    /**
     * 设置过期时间
     * @param null $expires 默认3600秒（可以通过修改config/qiniu.php/expires进行进行持久化修改）
     * @return $this
     */
    public function expires($expires = null)
    {
        $this->_expires = $expires ? intval($expires) : config('qiniu.expires');
        return $this;
    }

    /**
     * 获取私有空间图片链接
     * @param string $url 地址下载链接
     * @return string
     */
    public function getPrivateImage($url)
    {
        $qiniu_auth = new Auth($this->_access_key, $this->_secret_key);
        return $qiniu_auth->privateDownloadUrl($this->_prefix_url . $url, $this->_expires);
    }

    /**
     * 设置访问空间名称
     * @param null $bucket_name
     * @return $this
     */
    public function bucketName($bucket_name = null)
    {
        $this->_bucket_name = $bucket_name;
        return $this;
    }

    /**
     * 上传图片
     * @param \Illuminate\Http\UploadedFile|null $uploaded_file
     * @return HttpResponse
     */
    public function uploadImage(\Illuminate\Http\UploadedFile $uploaded_file = null)
    {
        $save_name = md5(Text::rand() . strval(time() * 1000)) . '.' . $uploaded_file->extension();
        $file_content = file_get_contents($uploaded_file->getPathname());
        $up = new UploadManager();
        $auth = new Auth($this->_access_key, $this->_secret_key);
        $token = $auth->uploadToken($this->_bucket_name);
        list($ret, $error) = $up->put($token, $save_name, $file_content);
        if (!$ret) return HttpResponse::fail()->details($error);
        return HttpResponse::success()->data([
            'filename' => $save_name,
            'extension' => $uploaded_file->extension(),
            'key' => $ret['key'],
        ]);

    }

    /**
     * 上传图片（使用原生方法）
     * @param mixed $file $_FILE
     * @return HttpResponse
     */
    public function uploadImageProto($file)
    {
        $save_name = md5(Text::rand() . strval(time() * 1000)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_content = file_get_contents($file['tmp_name']);
        $up = new UploadManager();
        $auth = new Auth($this->_access_key, $this->_secret_key);
        $token = $auth->uploadToken($this->_bucket_name);
        list($ret, $error) = $up->put($token, $save_name, $file_content);
        if(!$ret) return HttpResponse::fail()->details($error);
        return HttpResponse::success()->data([
            'filename' => $save_name,
            'extension' => pathinfo($file['name'], PATHINFO_EXTENSION),
            'key' => $ret['key'],
        ]);
    }
}
