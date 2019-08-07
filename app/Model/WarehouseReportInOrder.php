<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReportInOrder extends Model
{
    use SoftDeletes;

    public static $TYPE = [
        'BUY_IN' => '采购入库',
        'FIX_BY_SEND' => '维修入库',
        'FIX_TO_OUT_FINISH' => '送检返所',
    ];
    protected $fillable = [
        'serial_number',
        'processed_at',
        'processor_id',
        'send_processor_name',
        'send_processor_phone',
        'type',
    ];

    /**
     * 原类型
     * @return mixed
     */
    public function flipType()
    {
        return array_flip(self::$TYPE)[$this->value];
    }

    /**
     * 类型
     * @param $value
     * @return mixed
     */
    public function getTypeAttribute($value)
    {
        return self::$TYPE[$value];
    }

    /**
     * 处理人
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function processor()
    {
        return $this->hasOne(Account::class, 'id', 'processor_id');
    }

    /**
     * 入库设备实例
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warehouseReportInProductInstances()
    {
        return $this->hasMany(WarehouseReportInProductInstance::class, 'warehouse_report_in_order_serial_number', 'serial_number');
    }
}
