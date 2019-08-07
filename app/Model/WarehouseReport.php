<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReport extends Model
{
    use SoftDeletes;


    public static $TYPE = [
        'BUY_IN' => '采购入库',
        'INSTALLING' => '安装中',
        'INSTALLED' => '已安装',
        'FIXING' => '维修中',
        'ROTATE' => '轮换中',
        'FACTORY_RETURN' => '返厂中',
        'INSTALL' => '安装出库',
        'RETURN_FACTORY' => '返厂回所',
        'SCRAP' => '报废',
    ];

    public static $DIRECTION = [
        'IN' => '入所方向',
        'OUT' => '出所方向',
    ];

    protected $fillable = [
        'processor_id',
        'processed_at',
        'connection_name',
        'connection_phone',
        'type',
        'direction',
        'serial_number',
    ];

    public function prototype($attributeKey)
    {
        return $this->attributes[$attributeKey];
    }

    public function getTypeAttribute($value)
    {
        return self::$TYPE[$value];
    }

    public function getDirectionAttribute($value)
    {
        return self::$DIRECTION[$value];
    }

    public function Processor()
    {
        return $this->hasOne(Account::class, 'id', 'processor_id');
    }

    public function WarehouseReportEntireInstances()
    {
        return $this->hasMany(WarehouseReportEntireInstance::class, 'warehouse_report_serial_number', 'serial_number');
    }
}
