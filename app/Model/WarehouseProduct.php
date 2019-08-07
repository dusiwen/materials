<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProduct extends Model
{
    use SoftDeletes;

    public static $FIX_CYCLE_TYPE = [
        'YEAR' => '年',
        'MONTH' => '月',
        'WEEK' => '周',
        'DAY' => '日'
    ];
    protected $fillable = [
        'name',
        'organization_code',
        'category_open_code',
        'unique_code',
        'fix_cycle_type',
        'fix_cycle_value'
    ];

    public function flipFixCycleType()
    {
        return array_flip(self::$FIX_CYCLE_TYPE)[$this->fix_cycle_type];
    }

    public function getFixCycleTypeAttribute($value)
    {
        return self::$FIX_CYCLE_TYPE[$value];
    }

    public function warehouseProductParts()
    {
        return $this->belongsToMany(
            WarehouseProductPart::class,
            'pivot_from_warehouse_product_to_warehouse_product_parts',
            'warehouse_product_id',
            'warehouse_product_part_id'
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_open_code', 'open_code');
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class, 'warehouse_product_id', 'id');
    }
}
