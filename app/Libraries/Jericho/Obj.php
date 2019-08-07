<?php

namespace Jericho;
class Obj
{
    /**
     * 顺序查找法(单一查找,目标数组中没有重复项,找到第一个结束)
     * @author JerichoPH
     * @param array $arr 需要查找的目标数组
     * @param string $val 需要查找的内容
     * @return integer|string
     * 查找失败：-1
     * 查找成功：返回元素角标
     */
    public static function orderQuery($arr, $val)
    {
        foreach ($arr as $key => $value) {
            if ($value == $val) {
                return $key;
            }
        }
        return -1;
    }

    /**
     * 检查数组中元素是否存在
     * @author JerichoPH
     * @param array $arr array
     * @param string $key key name
     * @return bool
     */
    public static function has($arr, $key)
    {
        if (!isset($arr[$key]) || Str::strLen($arr[$key]) == 0 || is_null($arr[$key]) || empty($arr[$key])) {
            return false;
        }
        return true;
    }

    /**
     * 清除数组中所有元素不需要的内容
     * @author JerichoPH
     * @param array $arr 待过滤数组
     * @param string $limit ='' 过滤内容
     * @return array
     */
    public static function trim($arr, $limit = '')
    {
        foreach ($arr as $key => $item) {
            if (is_string($item)) {
                if ($limit == '') {
                    $arr[$key] = trim($item);
                } else {
                    $arr[$key] = trim($item, $limit);
                }
            } else {
                continue;
            }
        }
        return $arr;
    }

    /**
     * 删除数组中空值
     * @param array $arr 待过滤的数组
     * @return mixed
     */
    public static function unsetEmpty($arr)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value) || is_object($value)) {
                if (!$value) {
                    self::unsetEmpty($value);
                } else {
                    unset($arr[$key]);
                }
            } else {
                if (is_empty($value)) unset($arr[$key]);
            }
        }
        return $arr;
    }

    /**
     * 数组转对象
     * @param $E
     * @return Obj|void
     */
    public static function toObject($E)
    {
        if (gettype($E) != 'array') return;
        foreach ($E as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $E[$k] = (object)to_object($v);
        }
        return (object)$E;
    }

    /**
     * 对象转数组
     * @param $E
     * @return array|void
     */
    public static function toArray($E)
    {
        $E = (array)$E;
        foreach ($E as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $E[$k] = (array)to_array($v);
        }
        return $E;
    }

    /**
     * 数组转xml
     * @param $E
     * @return string
     */
    public static function toXml($E)
    {
        $xml = "<xml>";
        if (is_array($E)) {
            foreach ($E as $k => $v) {
                if (is_string($v)) {
                    $xml .= "<{$k}><!CDATA[[{$v}]]></{$k}>";
                } else {
                    $xml .= "<{$k}>{$v}</{$k}>";
                }
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 二维数组排序
     * @param array $multiArray 待排序数组
     * @param string $sortKey 排序依据字段
     * @param int $sort 排序方式
     * @return bool
     */
    public static function multiSort($multiArray, $sortKey, $sort = SORT_ASC)
    {
        if (is_array($multiArray)) {
            foreach ($multiArray as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sortKey];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_array, $sort, $multiArray);
        return $multiArray;
    }

    /**
     * 格式化json格式
     * @param mixed $val 待格式化内容
     * @param int $params 参数
     * @return string
     */
    public static function toJson($val, $params = 256)
    {
        return json_encode($val, $params);
    }

    /**
     * 解析json格式
     * @param string $json json格式字符串
     * @param bool $toArray 是否以array形式返回
     * @return mixed
     */
    public static function parseJson($json, $toArray = true)
    {
        return json_decode($json, $toArray);
    }

    /**
     * 生成多级树
     * @param array $data 待分级
     * @param int $parentId 父级编号
     * @return array
     */
    public static function getTree(array $data, int $parentId)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parentId) {        //父亲找到儿子
                $v['sub'] = self::getTree($data, $v['id']);
                $tree[] = $v;
//                unset($data[$k]);
            }
        }
        return $tree;
    }
}
