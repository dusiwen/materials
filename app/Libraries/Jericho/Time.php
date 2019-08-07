<?php

namespace Jericho;
/**
 * Class Time
 * @package Jericho
 */
class Time
{
    private static $_ins = null;
    private $_time_type = null;
    private $_timestamp = null;
    private $_datetime = null;
    private $_format = 'Y-m-d H:i:s';
    private $_result = null;

    private function __construct($time_type, $time = null)
    {
        $this->_time_type = $time_type;
        $this->$time_type = $time;
        switch ($this->_time_type) {
            case '_datetime':
                # 转换到时间戳
                $this->_timestamp = strtotime($this->_datetime);
                break;
            case '_timestamp':
                # 转换到日期时间
                $this->_datetime = date($this->_format, $this->_timestamp);
                break;
            default:
                # 使用万能格式数据
                $this->_timestamp = time();
                $this->_datetime = date($this->_format, $this->_timestamp);
                break;
        }
    }

    /**
     * 数据入口使用时间戳
     * @param null $timestamp 时间戳
     * @return Time|null
     */
    public static function fromTimestamp($timestamp = null)
    {
        if (!self::$_ins)
            self::$_ins = new self('_timestamp', $timestamp ? $timestamp : time());
        return self::$_ins;
    }

    /**
     * 数据入口使用日期
     * @param null $datetime 日期
     * @return Time|null
     */
    public static function fromDatetime($datetime = null)
    {
        $datetime = $datetime ? $datetime : date('Y-m-d H:i:s');
        if (!self::$_ins)
            self::$_ins = new self('_datetime', $datetime);
        return self::$_ins;
    }

    /**
     * 使用万能数据
     * @return Time|null
     */
    public static function fromAnyway()
    {
        if (!self::$_ins)
            self::$_ins = new self(null);
        return self::$_ins;
    }

    /**
     * 设置日期格式
     * @param null $format
     * @return $this
     */
    public function format($format = null)
    {
        $this->_format = $format != null ? $format : 'Y-m-d H:i:s';
        return $this;
    }

    /**
     * 转换到日期格式
     * @param null $method 修饰器方法名称
     * @return false|null|string
     */
    public function toDatetime($method = null)
    {
        # 转换到日期时间
        if ($method == null) return $this->_datetime;
        $this->_time_type = '_timestamp';
        return $this->$method();
    }

    /**
     * 转换到时间戳格式
     * @param null $method 修饰器方法名称
     * @return false|int|null
     */
    public function toTimestamp($method = null)
    {
        # 转换到时间戳
        if ($method == null) return $this->_timestamp;
        $this->_time_type = '_datetime';
        return $this->$method();
    }

    /**
     * 获取万能类型数据
     * @param null $method
     * @return mixed
     */
    public function toAnyway($method = null)
    {
        $this->_time_type = '_datetime';
        return $this->$method();
    }

    /**
     * 判断是否是闰年
     * @return bool
     */
    private function isLeapYear()
    {
        switch ($this->_time_type) {
            case '_datetime':
                $year = date('Y', $this->_timestamp);
                break;

            case '_timestamp':
                $year = date('Y', strtotime($this->_datetime));
                break;
        }
        return (($year % 4 == 0) && ($year % 100 != 0) || ($year % 400 == 0));
    }

    /**
     * 获取今年起始时间点
     * @return false|int|string
     */
    private function currentYearBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(0, 0, 0, 1, 1, date('Y'));
                break;
            case '_timestamp':
                return date($this->_format, mktime(0, 0, 0, 1, 1, date('Y')));
                break;
        }
    }

    /**
     * 获取今年终止时间点
     * @return false|int|string
     */
    private function currentYearEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(23, 59, 59, 12, 31, date('Y'));
                break;
            case '_timestamp':
                return date($this->_format, mktime(23, 59, 59, 12, 31, date('Y')));
                break;
        }
    }

    /**
     * 获取去年起始时间点
     * @return false|int|string
     */
    private function lastYearBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(0, 0, 0, 1, 1, date('Y') - 1);
                break;
            case '_timestamp':
                return date($this->_format, mktime(0, 0, 0, 1, 1, date('Y') - 1));
                break;
        }
    }

    /**
     * 获取去年终止时间点
     * @return false|int|string
     */
    private function lastYearEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(23, 59, 59, 12, 31, date('Y') - 1);
                break;
            case '_timestamp':
                return date($this->_format, mktime(23, 59, 59, 12, 31, date('Y') - 1));
                break;
        }
    }

    /**
     * 获取明年起始时间点
     * @return false|int|string
     */
    private function nextYearBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(0, 0, 0, 1, 1, date('Y') + 1);
                break;
            case '_timestamp':
                return date($this->_format, mktime(0, 0, 0, 1, 1, date('Y') + 1));
                break;
        }
    }

    /**
     * 获取明年终止时间点
     * @return false|int|string
     */
    private function nextYearEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(23, 59, 59, 12, 31, date('Y') + 1);
                break;
            case '_timestamp':
                return date($this->_format, mktime(23, 59, 59, 12, 31, date('Y') + 1));
                break;
        }
    }

    /**
     * 获取当月起始时间点
     * @return false|int|string
     */
    private function currentMonthBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(0, 0, 0, date('m'), 1, date('Y'));
                break;
            case '_timestamp':
                return date($this->_format, mktime(0, 0, 0, date('m'), 1, date('Y')));
                break;
        }
    }

    /**
     * 获取当月终止时间点
     * @return false|int|string
     */
    private function currentMonthEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(23, 59, 59, date('m'), date('t'), date('Y'));
                break;
            case '_timestamp':
                return date($this->_format, mktime(23, 59, 59, date('m'), date('t'), date('Y')));
                break;
        }
    }

    /**
     * 获取上个月起始时间点
     * @return false|int|string
     */
    private function lastMonthBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("-1 month",
                    mktime(0, 0, 0, date('m'), 1, date('Y'))
                );
                break;
            case '_timestamp':
                return date($this->_format,
                    strtotime("-1 month",
                        mktime(0, 0, 0, date('m'), 1, date('Y'))
                    )
                );
                break;
        }
    }

    /**
     * 获取上个月终止时间点
     * @return false|int|string
     */
    private function lastMonthEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("-1 month",
                    mktime(23, 59, 59, date('m'), date('t'), date('Y'))
                );
                break;
            case '_timestamp':
                return date($this->_format,
                    strtotime("-1 month",
                        mktime(23, 59, 59, date('m'), date('t'), date('Y'))
                    )
                );
                break;
        }
    }

    /**
     * 获取下个月起始时间点
     * @return false|int|string
     */
    private function nextMonthBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("+1 month",
                    date($this->_format, mktime(0, 0, 0, date('m'), 1, date('Y')))
                );
                break;
            case '_timestamp':
                return date($this->_format,
                    strtotime("+1 month", mktime(0, 0, 0, date('m'), 1, date('Y')))
                );
                break;
        }
    }

    /**
     * 获取下个月截止时间点
     * @return false|int|string
     */
    private function nextMonthEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("+1 month",
                    mktime(23, 59, 59, date('m'), date('t'), date('Y'))
                );
                break;
            case '_timestamp':
                return date($this->_format,
                    strtotime("+1 month", mktime(23, 59, 59, date('m'), date('t'), date('Y')))
                );
                break;
        }
    }

    /**
     * 获取本周起始时间点
     * @return false|int|string
     */
    private function currentWeekBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(0, 0, 0, date('m'), date('d') - date('w') - 6, date('Y'));
            case '_timestamp':
                return date($this->_format, mktime(0, 0, 0, date('m'), date('d') - date('w') - 6, date('Y')));
        }
    }

    /**
     * 获取本周截止时间点
     * @return false|int|string
     */
    private function currentWeekEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(23, 59, 59, date('m'), date('d') - date('w'), date('Y'));
            case '_timestamp':
                return date($this->_format, mktime(23, 59, 59, date('m'), date('d') - date('w'), date('Y')));
        }
    }

    /**
     * 获取上周起始时间点
     * @return false|int|string
     */
    private function lastWeekBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("-1 week",
                    mktime(0, 0, 0, date('m'), date('d') - date('w') - 6, date('Y'))
                );
            case '_timestamp':
                return date($this->_format,
                    strtotime("-1 week", mktime(0, 0, 0, date('m'), date('d') - date('w') - 6, date('Y')))
                );
        }
    }

    /**
     * 获取上周终止时间点
     * @return false|int|string
     */
    private function lastWeekEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("-1 week",
                    mktime(23, 59, 59, date('m'), date('d') - date('w'), date('Y'))
                );
            case '_timestamp':
                return date($this->_format,
                    strtotime("-1 week", mktime(23, 59, 59, date('m'), date('d') - date('w'), date('Y')))
                );
        }
    }

    /**
     * 获取下周起始时间点
     * @return false|int|string
     */
    private function nextWeekBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("+1 week",
                    mktime(0, 0, 0, date('m'), date('d') - date('w') - 6, date('Y'))
                );
            case '_timestamp':
                return date($this->_format,
                    strtotime("+1 week", mktime(0, 0, 0, date('m'), date('d') - date('w') - 6, date('Y')))
                );
        }
    }

    /**
     * 获取下周终止时间点
     * @return false|int|string
     */
    private function nextWeekEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("+1 week",
                    mktime(23, 59, 59, date('m'), date('d') - date('w'), date('Y'))
                );
            case '_timestamp':
                return date($this->_format,
                    strtotime("+1 week", mktime(23, 59, 59, date('m'), date('d') - date('w'), date('Y')))
                );
        }
    }

    /**
     * 判断是否是今天
     * @return bool
     */
    private function isToday()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return $this->todayBegin() < $this->_timestamp && $this->_timestamp < $this->todayEnd();
                break;
            case '_timestamp':
                return $this->todayBegin() < $this->_datetime && $this->_datetime < $this->todayEnd();
                break;
        }
    }

    /**
     * 获取今天起始时间点
     * @return false|int|string
     */
    private function todayBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                break;
            case '_timestamp':
                return date($this->_format,
                    mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                );
                break;
        }
    }

    /**
     * 获取今天终止时间点
     * @return false|int|string
     */
    private function todayEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return mktime(23, 59, 59, date('m'), date('d'), date('Y'));
                break;
            case '_timestamp':
                return date($this->_format,
                    mktime(23, 59, 59, date('m'), date('d'), date('Y'))
                );
                break;
        }
    }

    /**
     * 判断是否是昨天
     * @return bool
     */
    private function isYesterday()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return $this->yesterdayBegin() < $this->_timestamp && $this->_timestamp < $this->yesterdayEnd();
                break;
            case '_timestamp':
                return $this->yesterdayBegin() < $this->_datetime && $this->_datetime < $this->yesterdayEnd();
                break;
        }
    }

    /**
     * 获取昨天起始时间点
     * @return false|int|string
     */
    private function yesterdayBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("-1 day",
                    mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                );
                break;
            case '_timestamp':
                return date($this->_format,
                    strtotime("-1 day", mktime(0, 0, 0, date('m'), date('d'), date('Y')))
                );
                break;
        }
    }

    private function yesterdayEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("-1 day",
                    mktime(23, 59, 59, date('m'), date('d'), date('Y'))
                );
                break;
            case '_timestamp':
                return date($this->_format,
                    strtotime("-1 day", mktime(23, 59, 59, date('m'), date('d'), date('Y')))
                );
                break;
        }
    }

    /**
     * 判断是否是明天
     * @return bool
     */
    private function isTomorrow()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return $this->tomorrowBegin() < $this->_timestamp && $this->_timestamp < $this->tomorrowEnd();
                break;
            case '_timestamp':
                return $this->tomorrowBegin() < $this->_datetime && $this->_datetime < $this->tomorrowEnd();
                break;
        }
    }

    /**
     * 获取明天起始时间点
     * @return false|int|string
     */
    private function tomorrowBegin()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("+1 day",
                    mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                );
                break;
            case '_timestamp':
                return date($this->_format,
                    strtotime("+1 day", mktime(0, 0, 0, date('m'), date('d'), date('Y')))
                );
                break;
        }
    }

    /**
     * 获取明天终止时间点
     * @return false|int|string
     */
    private function tomorrowEnd()
    {
        switch ($this->_time_type) {
            case '_datetime':
                return strtotime("+1 day",
                    mktime(23, 59, 59, date('m'), date('d'), date('Y'))
                );
                break;
            case '_timestamp':
                return date($this->_format,
                    strtotime("+1 day", mktime(23, 59, 59, date('m'), date('d'), date('Y')))
                );
                break;
        }
    }
}
