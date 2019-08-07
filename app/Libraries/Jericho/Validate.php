<?php

namespace Jericho;

use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;

class Validate
{
    use Helpers;

    /**
     * 检查字符串是否是邮箱
     * @author JerichoPH
     * @param string $val string
     * @return bool
     */
    public static function isEmail($val)
    {
        if (!is_string($val) && !self::isEmpty($val)) {
            return '字段必须是字符串';
        }
//		$res = preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $val);
        $res = preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $val);
        if ($res == 1) {
            return true;
        }
        return '格式不符合邮箱格式';
    }

    /**
     * 检查字符串是否为空
     * @author JerichoPH
     * @param string $val variable
     * @return bool
     */
    public static function isEmpty($val)
    {
        if (!isset($val)) {
            if (!$val) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判断是否是数字（内部）
     * @param string $val 待验证字段
     * @return int
     */
    public static function isNum($val)
    {
        return boolval(preg_match("/^[0-9]*\.?[0-9]*$/", $val));
    }

    /**
     * 判断参数是否是汉字（内部）
     * @param string $val 字符串
     * @return bool|null
     */
    public static function hasChs($val)
    {
        $pattern = '/[^\x00-\x80]/';
        if (preg_match($pattern, $val)) {
            return true;
        } else {
            return '不含有中文';
        }
    }

    /**
     * 判断参数是否是一个url
     * @param string $val 字符串
     * @return bool|null
     */
    public static function isUrl($val)
    {
        $res = preg_match("/^http://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?$ ；^[a-zA-z]+://(w+(-w+)*)(.(w+(-w+)*))*(?S*)?$/", $val);
        if ($res == 1) {
            return true;
        } elseif ($res == 0) {
            return null;
        }
        return false;
    }

    /**
     * 判断是参数是否是一个密码
     * @param string $val 字符串
     * @return bool|null
     */
    public static function isPwd($val)
    {
        $res = preg_match("/^[a-zA-Z]\w{5,17}$/", $val);
        if ($res == 1) {
            return true;
        } elseif ($res == 0) {
            return null;
        }
        return false;
    }

    /**
     * 检查变量是否是手机号
     * @author JerichoPH
     * @param mixed $val variable
     * @return bool
     */
    public static function isPhone($val)
    {
        $val = (string)$val;
        $value_len = Text::strLen($val);
        if ($value_len != 11) {
            return false;
        }
        if (preg_match("/^13[0-9]{1}[0-9]{8}$|14[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|16[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", $val)) {
            //验证通过
            return true;
        } else {
            //手机号码格式不对
            return false;
        }
    }

    /**
     * 检查变量是否是身份证号
     * @author JerichoPH
     * @param mixed $val variable
     * @return bool
     */
    public static function isPid($val)
    {
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );

        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $val)) return false;

        if (!in_array(substr($val, 0, 2), $vCity)) return false;

        $val = preg_replace('/[xX]$/i', 'a', $val);
        $vLength = strlen($val);

        if ($vLength == 18) {
            $vBirthday = substr($val, 6, 4) . '-' . substr($val, 10, 2) . '-' . substr($val, 12, 2);
        } else {
            $vBirthday = '19' . substr($val, 6, 2) . '-' . substr($val, 8, 2) . '-' . substr($val, 10, 2);
        }

        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18) {
            $vSum = 0;

            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($val, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }

            if ($vSum % 11 != 1) return false;
        }
        return true;
    }

    /**
     * 获取laravel 表单验证错误的第一个返回值
     * @param mixed $data 待验证数据
     * @param mixed $request 验证类
     * @param mixed $response 相应格式
     * @param integer|string $errorCode 错误码
     * @return bool
     */
    public static function firstErrorByRequest(\Illuminate\Http\Request $data, $request, $response = null, $errorCode = 420)
    {
        $v = Validator::make($data->all(), $request->rules(), $request->messages());
        $errorMessage = $v->errors()->first();
        if ($v->fails()) return $response ? $response->error($errorMessage, $errorCode) : $errorMessage;
        return true;
    }

    public static function firstError(array $data, $request)
    {
        $v = Validator::make($data, $request->rules(), $request->messages());
        $errorMessage = $v->errors()->first();
        if ($v->fails()) return $errorMessage;
        return true;
    }

    /**
     * 获取laravel 表单验证全部错误
     * @param mixed $data 待验证数据
     * @param mixed $request 验证类
     * @param mixed $response 相应格式
     * @param integer|string $errorCode 错误码
     * @return bool
     */
    public static function entireErrorsByRequest(\Illuminate\Http\Request $data, $request, $response = null, $errorCode = 420)
    {
        $v = Validator::make($data->all(), $request->rules(), $request->messages());
        $errorMessage = $v->errors() ?: null;
        if ($v->fails()) return $response ? $response->error($errorMessage, $errorCode) : $errorMessage;
        return true;
    }

    public static function entireErrors(array $data, $request)
    {
        $v = Validator::make($data->all(), $request->rules(), $request->messages());
        $errorMessage = $v->errors() ?: null;
        if ($v->fails()) return $errorMessage;
        return true;
    }
}
