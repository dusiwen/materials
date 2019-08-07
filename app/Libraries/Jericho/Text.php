<?php

namespace Jericho;
class Text
{
    /**
     * 绝对字符串截取
     * @author JerichoPH
     * @param string $STR string
     * @param string $START start to cut out
     * @param string $LENGTH length to cut out
     * @return string
     */
    public static function sub($STR, $START, $LENGTH)
    {
        if (self::strLen($STR) == 0) {
            return $STR;
        }
        $str = preg_split('//u', $STR, -1, PREG_SPLIT_NO_EMPTY);
        $res = '';
        $LENGTH = $LENGTH <= count($str) ? $LENGTH : count($str);
        for ($i = $START; $i < $LENGTH; $i++) {
            $res .= $str[$i];
        }
        return $res;
    }

    /**
     * 取绝对字符串长度
     * JerichoPH
     * @param string $STR string
     * @return bool|int
     */
    public static function strLen($STR)
    {
        if (!is_string($STR)) {
            return false;
        }
        return count(preg_split('//u', $STR, -1, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * 设置变量默认值
     * @author JerichoPH
     * @param mixed $val variable
     * @param mixed $default default value. default: 无
     * @return mixed
     */
    public static function def($val, $default = '无')
    {
        return Validate::isEmpty($val) ? $default : $val;
    }

    /**
     * 加密字符串
     * @author JerichoPH
     * @param string $data source sting
     * @param string $key key to do make secret
     * @return string
     */
    public static function enSecret($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $data_len = strlen($data);
        $key_len = strlen($key);
        $char = "";
        $str = "";
        for ($i = 0; $i < $data_len; $i++) {
            if ($x == $key_len) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $data_len; $i++) {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
     * 解密字符串
     * @author JerichoPH
     * @param string $data source string
     * @param string $key key to do make secret
     * @return string
     */
    public static function deSecret($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char = "";
        $str = "";
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    /**
     * 密码加盐
     * @param string $pass 密码内容
     * @param int $saltLen 盐长度
     * @param bool $returnArray 是否返回数组
     * @param null $salt 盐s
     * @param string $saltType 盐类型（如果是随机盐）
     * @param bool string 原密码是否需要再次加密
     * @return array
     */
    public static function enSalt($pass, $saltLen = 6, $returnArray = true, $salt = null, $saltType = 'Admix', $md5 = true)
    {
        $salt = trim($salt);
        $salt = $salt == null || self::strLen($salt) < $saltLen ? self::rand($saltType, $saltLen) : $salt;
        $pass = $md5 == true ? md5($pass) : $pass;
        return $returnArray ? [md5($pass . $salt), $salt] : ['pass' => md5($pass . $salt), 'salt' => $salt];
    }

    /**
     * 生成随机字符串
     * @author JerichoPH
     * @param string $TYPE ='admix' make char type
     * @explain:
     *         admix: lower char and numeric
     *         Admix: lower char and upper char and numeric
     *         ADMIX: upper char and numeric
     *         string: only lower char
     *         String: lower char upper char
     *         STRING: only upper char
     *         num: only numeric
     * @param integer $LENGTH =8 生成长度
     * @return string
     */
    public static function rand($TYPE = 'Admix', $LENGTH = 32)
    {
        //dictionary
        $dictionary = array(
            'string' => 'qwertyuiopasdfghjklzxcvbnm',
            'STRING' => 'QWERTYUIOPASDFGHJKLZXCVBNM',
            'String' => 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM',
            'admix' => 'q1we3rty2ui6opa4sdf7ghj5klz8xc9vbn0m',
            'ADMIX' => 'Q1WE3RTY2UI6OPA4SDF7GHJ5KLZ8XC9VBN0M',
            'Admix' => 'Q1WE3RTY2UI6OPA4SDF7GHJ5KLZ8XC9VBN0Mq1we3rty2ui6opa4sdf7ghj5klz8xc9vbn0m',
            'num' => '1234567890'
        );
        $type = 'admix';
        if (empty($TYPE) == false) {
            $type = trim($TYPE);
        }
        $length = 8;
        if ($LENGTH > 1) {
            $length = (int)$LENGTH;
        }
        $str = '';
        switch ($type) {
            case 'string' :
                for ($i = 0; $i < $length; $i++) {
                    $str .= $dictionary{$type}{rand(0, 25)};
                }
                break;
            case 'STRING' :
                for ($i = 0; $i < $length; $i++) {
                    $str .= $dictionary{$type}{rand(0, 25)};
                }
                break;
            case 'String' :
                for ($i = 0; $i < $length; $i++) {
                    $str .= $dictionary{$type}{rand(0, 51)};
                }
                break;
            case 'admix' :
                for ($i = 0; $i < $length; $i++) {
                    $str .= $dictionary{$type}{rand(0, 35)};
                }
                break;
            case 'ADMIX' :
                for ($i = 0; $i < $length; $i++) {
                    $str .= $dictionary{$type}{rand(0, 35)};
                }
                break;
            case 'Admix' :
                for ($i = 0; $i < $length; $i++) {
                    $str .= $dictionary{$type}{rand(0, 71)};
                }
                break;
            case 'num' :
                for ($i = 0; $i < $length; $i++) {
                    $str .= $dictionary[$type][rand(0, 9)];
                }
                break;
        }
        return $str;
    }

    /**
     * 密码解盐
     * @param string $inputPass 表单输入密码
     * @param string $salt 盐
     * @param string $sourcePass 原密码
     * @param bool $md5 原密码是否需要md5加密
     * @return bool
     */
    public static function deSalt($inputPass, $salt, $sourcePass, $md5 = true)
    {
        $salt = trim($salt);
        return md5(($md5 == true ? md5($inputPass) : $inputPass) . $salt) == $sourcePass;
    }
}