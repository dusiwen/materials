<?php

namespace Jericho;
class Request
{
    /**
     * 发送请求
     * @author JerichoPH
     * @param string $url target url
     * @param string $type request type. optional: get or post.
     * @param null $data request post use this data.
     * @param bool|true $ssl this request is use ssl verify.
     * @return string response
     */
    public static function send($url, $type, $data = null, $ssl = true)
    {
        # 使用curl发送协议
        $curl = curl_init();
        # curl请求相关设置
        curl_setopt($curl, CURLOPT_URL, $url);
        # 发送请求目标地址
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        # 设置请求代理信息
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        # 开启自动请求头
        # SSL相关设置
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            # 终止服务器端验证SSL（建议在对方是明确安全的服务器时使用）
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            # 检查服务器SSL证书中是否存在一个公用名（common name）
        }
        # curl响应相关设置
        switch (strtoupper($type)) {
            case 'GET' :
                # 发送get请求
                curl_setopt($curl, CURLOPT_HEADER, false);
                # 不处理响应头
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                # 是否返回响应结果
                break;
            case 'POST' :
                # 发送post请求
                curl_setopt($curl, CURLOPT_POST, true);
                # 处理post响应信息
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                # 处理请求数据
                curl_setopt($curl, CURLOPT_HEADER, false);
                # 禁止处理响应头信息
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                # 开启返回相应结果
                break;
            case 'PUT':
                # 发送PUT请求
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }
        # 发送请求
        $response = curl_exec($curl);
        if ($response == false) {
            return curl_error($curl);
        }

        return $response;
    }

    /**
     * 发送get请求
     * @param string $url URL地址
     * @param array $data URL参数
     * @param bool $ssl 是否检查SSL
     * @return mixed|string
     */
    public function get($url, $data = null, $ssl = false)
    {
        # 使用curl发送协议
        $curl = curl_init();
        # 拼接URL参数
        if ($data) $url_data = Url::buildParams($data, $url);

        # curl请求相关设置
        curl_setopt($curl, CURLOPT_URL, $url);
        # 发送请求目标地址
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        # 设置请求代理信息
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        # 开启自动请求头
        # SSL相关设置
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            # 终止服务器端验证SSL（建议在对方是明确安全的服务器时使用）
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            # 检查服务器SSL证书中是否存在一个公用名（common name）
        }

        curl_setopt($curl, CURLOPT_HEADER, false);
        # 不处理响应头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        # 是否返回响应结果

        # 发送请求
        $response = curl_exec($curl);
        if ($response == false) {
            return curl_error($curl);
        }

        return $response;
    }

    /**
     * 发送post请求
     * @param string $url URL地址
     * @param array $data 发送数据
     * @param bool $ssl 是否检查SSL
     * @return mixed|string
     */
    public static function post($url, $data = null, $ssl = false)
    {
        # 使用curl发送协议
        $curl = curl_init();

        # curl请求相关设置
        curl_setopt($curl, CURLOPT_URL, $url);
        # 发送请求目标地址
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        # 设置请求代理信息
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        # 开启自动请求头
        # SSL相关设置
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            # 终止服务器端验证SSL（建议在对方是明确安全的服务器时使用）
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            # 检查服务器SSL证书中是否存在一个公用名（common name）
        }

        # 发送post请求
        curl_setopt($curl, CURLOPT_POST, true);
        # 处理post响应信息
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        # 处理请求数据
        curl_setopt($curl, CURLOPT_HEADER, false);
        # 禁止处理响应头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        # 开启返回相应结果

        # 发送请求
        $response = curl_exec($curl);
        if ($response == false) {
            return curl_error($curl);
        }

        return $response;
    }

    /**
     * 发送地址
     * @param string $url 发送地址
     * @param array $data 发送数据
     * @param bool $ssl
     * @return mixed|string
     */
    public static function put($url, $data = null, $ssl = false)
    {
        # 使用curl发送协议
        $curl = curl_init();

        # curl请求相关设置
        curl_setopt($curl, CURLOPT_URL, $url);
        # 发送请求目标地址
        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        # 设置请求代理信息
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        # 开启自动请求头
        # SSL相关设置
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            # 终止服务器端验证SSL（建议在对方是明确安全的服务器时使用）
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            # 检查服务器SSL证书中是否存在一个公用名（common name）
        }
        # 发送post请求
        # 设置请求方式
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        # 设置提交的字符串
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        # 处理请求数据
        curl_setopt($curl, CURLOPT_HEADER, false);
        # 禁止处理响应头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        # 发送请求
        $response = curl_exec($curl);
        if ($response == false) {
            return curl_error($curl);
        }

        return $response;
    }

    /**
     * 远程获取图片
     * @param string $url 获取地址
     * @param string $saveDir 保存地址
     * @param string $filename 保存文件名
     * @param bool $isNetworkImage 是否是网络图片
     * @return array
     */
    public static function getImage($url, $saveDir = '', $filename = '', $isNetworkImage = true)
    {

        if (trim($url) == '') {
            return array('file_name' => '', 'save_path' => '', 'error' => 1);
        }
        if (trim($saveDir) == '') {
            $saveDir = './';
        }
        if (trim($filename) == '') {//保存文件名
            $ext = strrchr($url, '.');
            if ($ext != '.gif' && $ext != '.jpg') {
                return array('file_name' => '', 'save_path' => '', 'error' => 3, 'details' => $ext);
            }
            $filename = time() . $ext;
        }
        if (0 !== strrpos($saveDir, '/')) {
            $saveDir .= '/';
        }
        //创建保存目录
        if (!file_exists($saveDir) && !mkdir($saveDir, 0777, true)) {
            return array('file_name' => '', 'save_path' => '', 'error' => 5);
        }
        //获取远程文件所采用的方法
        if ($isNetworkImage) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $img = curl_exec($ch);
            curl_close($ch);
        } else {
            ob_start();
            readfile($url);
            $img = ob_get_contents();
            ob_end_clean();
        }
        //$size=strlen($img);
        //文件大小
        $fp2 = @fopen($saveDir . $filename, 'a');
        fwrite($fp2, $img);
        fclose($fp2);
        unset($img, $url);
        return array('file_name' => $filename, 'save_path' => $saveDir . $filename, 'error' => 0);
    }
}