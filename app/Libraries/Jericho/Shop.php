<?php

namespace Jericho;
class Shop
{
    /**
     * 笛卡尔乘积算法
     * @param $data
     * @return array
     */
    public static function combineDika($data)
    {
        $result = array();
        foreach (array_shift($data) as $k => $item) {
            $result[] = array($k => $item);
        }


        foreach ($data as $k => $v) {
            $result2 = [];
            foreach ($result as $k1 => $item1) {
                foreach ($v as $k2 => $item2) {
                    $temp = $item1;
                    $temp[$k2] = $item2;
                    $result2[] = $temp;
                }
            }
            $result = $result2;
        }
        return $result;
    }
}