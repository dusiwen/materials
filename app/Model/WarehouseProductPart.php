<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProductPart extends Model
{
    use SoftDeletes;

    public static $FIX_CYCLE_TYPE = [
        'YEAR' => '年',
        'MONTH' => '月',
        'DAY' => '日',
    ];
    protected $fillable = [
        'name',
        'subscript',
        'prefix_name',
        'prefix_subscript',
        'inventory',
        'character',
        'fix_cycle_type',
        'fix_cycle_value',
        'category_open_code'
    ];

    public function getFixCycleTypeAttribute($value)
    {
        return self::$FIX_CYCLE_TYPE[$value];
    }

    public function flipFixCycleType($value)
    {
        return array_flip(self::$FIX_CYCLE_TYPE)[$value];
    }

    public function warehouseProducts()
    {
        return $this->belongsToMany(
            WarehouseProduct::class,
            'pivot_from_warehouse_product_to_warehouse_product_parts',
            'warehouse_product_part_id',
            'warehouse_product_id'
        );
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'open_code', 'category_open_code');
    }
}
