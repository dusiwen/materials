<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReportOutOrder extends Model
{
    use SoftDeletes;

    public static $TYPE = [
        'INSTALL' => '安装出库',
        'FIX_TO_OUT' => '出所送检',
        'SCRAP' => '报废'
    ];

    protected $fillable = [
        'serial_number',
        'processed_at',
        'processor_id',
        'draw_processor_name',
        'draw_processor_phone',
        'type'
    ];

    public function getTypeAttribute($value)
    {
        return self::$TYPE[$value];
    }

    public function flipType()
    {
        return array_flip(self::$TYPE)[$this->type];
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
     * 出库设备实例
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warehouseReportOutProductInstances()
    {
        return $this->hasMany(WarehouseReportOutProductInstance::class, 'warehouse_report_out_order_serial_number', 'serial_number');
    }

}
