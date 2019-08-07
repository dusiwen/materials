<?php

namespace Jericho;

use Illuminate\Support\Facades\DB;

class MySQL
{
    private static $_ins = null;
    private $_version = 'v1';
    private $_params = null;
    private $_paramNames = null;
    private $_returns = null;
    private $_name = null;
    private $_sql = null;

    /**
     * MySQL constructor.
     * @param null $version
     */
    private function __construct($version = null)
    {
        $this->_version = $version ?: 'v1';
    }

    /**
     * 获取本类对象
     * @param null $version
     * @return MySQL
     */
    public static function ins($version = null)
    {
        if (!self::$_ins) self::$_ins = new self($version);
        return self::$_ins;
    }

    /**
     * 执行存储过程
     * @param string $name
     * @param array $params
     * @return $this
     */
    public function procedure(string $name, array $params = null)
    {
        $this->_paramNames = '';
        if ($params) {
            for ($i = 0; $i < count($params); $i++) {
                $this->_paramNames .= '?,';
            }
        }

        $this->_params = $params;
        $this->_name = $name;

        return $this;
    }

    /**
     * 生成sql语句
     * @param string $type 类型
     * @param array ...$returns
     * @return bool|string|null
     */
    private function buildSql($type, ...$returns)
    {
        $this->_returns = '';
        if (!empty($returns[0])) {
            $this->_returns = implode(',', $returns[0]);
            $parameter = $this->_paramNames . $this->_returns;
        } else {
            $parameter = rtrim($this->_paramNames, ',');
        }

        switch ($type) {
            case 'read':
                $this->_sql = "CALL {$this->_name}__{$this->_version} ({$parameter})";
                return true;
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * 获取sql语句
     * @param string $type 类型
     * @param array ...$returns
     * @return string
     */
    public function sql($type, ...$returns)
    {
        self::buildSql($type, $returns);
        return $this->_sql;
    }

    /**
     * 读取数据
     * @param mixed ...$returns
     * @return array|bool|string
     */
    public function read(...$returns)
    {
        self::buildSql('read', $returns);

        $return = DB::select($this->_sql, $this->_params);
        if (!empty($returns[0])) {
            $return = DB::select('select ' . $this->_returns);
            if(!empty($return)){
                $result = [];
                foreach ($returns as $item) {
                    $result[ltrim($item, '@')] = $return[0]->$item;
                }
                return $result;
            }else{
                return null;
            }
        }else{
            return $return;
        }
    }
}
