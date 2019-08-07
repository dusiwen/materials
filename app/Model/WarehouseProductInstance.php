<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProductInstance extends Model
{
    use SoftDeletes;

    public static $STATUS = [
        'NONE' => '无',
        'BUY_IN' => '采购入库',
        'INSTALLING' => '出库安装未返回位置',
        'INSTALLED' => '已安装',
        'FIX_BY_SEND' => '返修入库',
        'FIX_AT_TIME' => '定期维护',
        'FIX_TO_OUT' => '出所送检',
        'FIX_TO_OUT_FINISH' => '出所送检完成',
        'SCRAP' => '报废',
        'FIXED' => '检修完成'
    ];

    protected $fillable = [
        'open_code',
        'warehouse_product_unique_code',
        'status',
        'factory_unique_code',
        'factory_device_code',
        'maintain_unique_code',
        'fix_workflow_id',
        'fix_workflow_process_id',
        'is_using',
        'installed_at',
        'last_fixed_time'
    ];

    public static function flipStatus($value)
    {
        return array_flip(self::$STATUS)[$value];
    }

    public function warehouseProduct()
    {
        return $this->hasOne(WarehouseProduct::class, 'unique_code', 'warehouse_product_unique_code');
    }

    public function factory()
    {
        return $this->hasOne(Factory::class, 'unique_code', 'factory_unique_code');
    }

    public function maintain()
    {
        return $this->hasOne(Maintain::class, 'unique_code', 'maintain_unique_code');
    }

    public function fixWorkflows()
    {
        return $this->hasMany(FixWorkflow::class, 'id', 'fix_workflow_id');
    }

    public function getStatusAttribute($value)
    {
        return self::$STATUS[$value];
    }
}
