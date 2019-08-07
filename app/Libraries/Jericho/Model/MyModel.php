<?php

namespace Jericho\Model;
use Illuminate\Support\Facades\DB;

class MyModel
{
    private static $_ins = null;
    private $_insertData = null;

    private function __construct($data, \Closure $closure = null)
    {
        $this->insertData($data, $closure);
    }

    /**
     * 插入数据
     * @param array|string|null $data
     * @param \Closure $closure 回调函数
     * @return null
     */
    public function insertData($data = null, \Closure $closure = null)
    {
        if ($data === null) return $this->_insertData;

        if (is_string($data)) $data = array_unique(explode(',', $data));

        if ($closure !== null) {
            foreach ($data as $item) {
                $this->_insertData[] = $closure($item);
            }
            return true;
        } else {
            $this->_insertData = $data;
            return true;
        }
    }

    /**
     * 获取本类对象
     * @param array|string $data 待插入数据
     * @param \Closure|null $closure
     * @return null
     */
    public static function ins($data, \Closure $closure = null)
    {
        if (!self::$_ins) new self($data, $closure);
        return self::$_ins;
    }

    /**
     * 批量插入数据
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function inserts(\Illuminate\Database\Eloquent\Model $model)
    {
        return DB::table($model->getTable())->insert($this->_insertData);
    }

    /**
     * 批量插入数据（不重复）
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function insertsUnique(\Illuminate\Database\Eloquent\Model $model)
    {
        try {
            foreach ($this->_insertData as $item) {
                $model->firstOrCreate($item);
            }
        } catch (\Exception $exception) {
            $exception->getMessage();
        }
    }
}
