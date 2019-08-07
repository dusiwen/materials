<?php

namespace Jericho;
class Tree
{
    const PARENT_ID = 'parent_id';
    const ID = 'id';
    const CHILDREN = 'sub';

    public static function make(array $items)
    {
        $children = [];
        // group by parent id
        foreach ($items as &$item) {
            $children[$item[self::PARENT_ID]][] = &$item;
            dump($item[self::PARENT_ID]);
            unset($item);
        }
        foreach ($items as &$item) {
            $pid = $item[self::ID];
            if (array_key_exists($pid, $children)) {
                $item[self::CHILDREN] = $children[$pid];
            }
            unset($item);
        }
        return $children;
    }
}
