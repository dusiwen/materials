<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReportProduct extends Model
{
    use SoftDeletes;

    public static $IN_REASON = [
        'NONE' => '无',
        'BUY_IN' => '采购入库',
        'FIX_BY_SEND' => '返修',
        'FIX_AT_TIME' => '定期维护',
        'FIX_TO_OUT_FINISH' => '出所送检返回',
    ];
    public static $OUT_REASON = [
        'NONE' => '无',
        'INSTALL_OUT' => '安装出库',
        'FIX_BY_SEND_FINISH' => '返修完成',
        'FIX_TO_OUT' => '出所送检',
        'SCRAP' => '报废',
    ];

    public static $OPERATION_DIRECTION = [
        'IN' => '入库',
        'OUT' => '出库'
    ];

    protected $fillable = [
        'draw_person_name',
        'draw_person_phone',
        'out_person_id',
        'outed_at',
        'maintain_id',
        'in_person_id',
        'send_person_name',
        'send_person_phone',
        'in_at',
        'description',
        'in_reason',
        'out_reason',
        'warehouse_product_instance_open_code',
        'operation_direction'
    ];

    public static function flipInReason($value)
    {
        return array_flip(self::$IN_REASON)[$value];
    }

    public static function flipOutReason($value)
    {
        return array_flip(self::$OUT_REASON)[$value];
    }

    public function flipOperationDirection($value)
    {
        return array_flip(self::$OPERATION_DIRECTION)[$value];
    }

    public function getOperationDirectionAttribute($value)
    {
        return self::$OPERATION_DIRECTION[$value];
    }

    public function setOrganizationCodeAttribute($value)
    {
        if (!$value) $this->attributes['organization_code'] = env('ORGANIZATION_CODE');
    }

    public function outPerson()
    {
        return $this->hasOne(Account::class, 'id', 'out_person_id');
    }

    public function inPerson()
    {
        return $this->hasOne(Account::class, 'id', 'in_person_id');
    }

    public function maintain()
    {
        return $this->hasOne(Maintain::class, 'id', 'maintain_id');
    }

    public function warehouseProductInstance()
    {
        return $this->hasOne(WarehouseProductInstance::class, 'open_code', 'warehouse_product_instance_open_code');
    }

    public function getInReasonAttribute($value)
    {
        return self::$IN_REASON[$value];
    }

    public function getOutReasonAttribute($value)
    {
        return self::$OUT_REASON[$value];
    }
}
