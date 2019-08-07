<?php

namespace Jericho;
class HttpResponse
{
    private static $_ins = null;

    public $flag = true;  # 返回是否正确标识
    public $json = null;  # json格式返回值
    public $object = null;  # 对象格式返回值
    public $array = null;  # 数组格式返回值

    private $_result = null;  # 错误标识
    private $_statusCode = null;  # 状态码
    private $_errorCode = null;  # 错误码
    private $_message = null;  # 返回说明信息
    private $_data = null;  # 返回数据
    private $_details = null;  # 详细描述（一般用于错误）
    private $_dataFormat = null;  # 返回数据类型
    private $_style = null;  # 文本样式标签
    private $_request = null;  # 设置请求参数
    private $_nextRedirect = null;  # 设置后续跳转


    private function __construct()
    {
    }

    /**
     * 返回读取信息
     * @param null $dataFormat 返回数据类型 object|array|paginate
     * @return HttpResponse
     */
    public static function read($dataFormat = null)
    {
        self::ins();

        self::$_ins->FLAG = true;
        self::$_ins->_result = 'success';
        self::$_ins->_style = 'success';
        self::$_ins->_statusCode = 200;
        self::$_ins->_errorCode = 0;
        self::$_ins->_message = '读取成功';
        self::$_ins->_dataFormat = $dataFormat;

        # 创建标准返回值内容
        return self::$_ins->build();
    }

    /**
     * 单例
     * @return HttpResponse
     */
    private static function ins()
    {
        if (!self::$_ins) self::$_ins = new self;
        return self::$_ins;
    }

    /**
     * 构建返回值
     * @return HttpResponse
     */
    private function build()
    {
        $this->array = [
            'result' => $this->_result,
            'message' => $this->_message,
            'status' => $this->_statusCode,
            'error' => $this->_errorCode,
            'data' => $this->_data,
            'details' => $this->_details,
            'style' => $this->_style,
            'data_format' => $this->_dataFormat ?: gettype($this->_data),
            'flag' => $this->flag,
            'next_redirect' => $this->_nextRedirect,
        ];

        $this->object = (object)$this->array;

        $this->json = json_encode($this->array, 256);

        return $this;
    }

    /**
     * 正确信息
     * @param null $message 信息描述
     * @return HttpResponse
     */
    public static function success($message = null)
    {
        self::ins();

        self::$_ins->FLAG = true;
        self::$_ins->_result = 'success';
        self::$_ins->_style = 'success';
        self::$_ins->_statusCode = 200;
        self::$_ins->_errorCode = 0;
        self::$_ins->_message = $message ? $message : '操作成功';

        # 创建标准返回值内容
        return self::$_ins->build();
    }

    /**
     * 一般错误
     * @param null $msg 错误描述
     * @param int $error_code 错误码
     * @param int $status_code 状态码
     * @return HttpResponse
     */
    public static function fail($msg = null, $error_code = 1, $status_code = 500)
    {
        $my = self::ins();

        self::$_ins->FLAG = false;
        self::$_ins->_result = 'fail';
        self::$_ins->_style = 'danger';
        self::$_ins->_statusCode = $status_code;
        self::$_ins->_errorCode = $error_code;
        self::$_ins->_message = $msg ? $msg : '操作失败';

        # 创建标准返回值内容
        return self::$_ins->build();
    }

    /**
     * 空数据
     * @param string $message 错误信息描述
     * @param int $error_code 错误码
     * @return HttpResponse
     */
    public static function null($message = null, $error_code = 1)
    {
        self::ins();

        self::$_ins->FLAG = false;
        self::$_ins->_result = 'empty';
        self::$_ins->_style = 'danger';
        self::$_ins->_statusCode = 404;
        self::$_ins->_errorCode = $error_code;
        self::$_ins->_message = $message ? $message : '空数据';

        # 创建标准返回值内容
        return self::$_ins->build();
    }

    /**
     * 数据重复
     * @param string $msg 错误信息描述
     * @param int $error_code 错误码
     * @return HttpResponse
     */
    public static function repeat($msg = null, $error_code = 1)
    {
        $my = self::ins();

        self::$_ins->FLAG = false;
        self::$_ins->_result = 'repeat';
        self::$_ins->_style = 'danger';
        self::$_ins->_statusCode = 403;
        self::$_ins->_errorCode = $error_code;
        self::$_ins->_message = $msg ? $msg : '数据重复';

        # 创建标准返回值内容
        return self::$_ins->build();
    }

    /**
     * 设置后续跳转
     * @param null $next_redirect 后续跳转地址
     * @return HttpResponse
     */
    public function nextRedirect($next_redirect = null)
    {
        $this->_nextRedirect = $next_redirect;
        return self::$_ins->build();
    }

    /**
     * 设置请求参数
     * @param null $request
     * @return HttpResponse
     */
    public function request($request = null)
    {
        $this->_request = $request;
        return self::$_ins->build();
    }

    /**
     * 设置返回数据集
     * @param null $data
     * @return HttpResponse
     */
    public function data($data = null)
    {
        $this->_data = $data;
        return self::$_ins->build();
    }

    /**
     * 设置详细说明
     * @param null $details 详细说明
     * @return HttpResponse
     */
    public function details($details = null)
    {
        $this->_details = $details;
        return self::$_ins->build();
    }

    /**
     * 清空本次返回值
     */
    private function clear()
    {
        # 清空本次返回值
        $this->_result = null;# 错误标识
        $this->_statusCode = null; # 错误码
        $this->_message = null;# 返回说明信息
        $this->_data = null;# 返回数据
        $this->_details = null;# 详细说明
        $this->_style = null;# 文本样式标签
        $this->flag = true;# 返回是否正确标识
    }
}
