<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProcurementPartInstance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'warehouse_procurement_part_id',
        'warehouse_product_part_id',
        'number',
    ];

    public function warehouseProcurementPart()
    {
        return $this->belongsTo(WarehouseProductPart::class,'id','warehouse_procurement_part_id');
    }

    public function warehouseProductPart()
    {
        return $this->hasOne(WarehouseProductPart::class,'id','warehouse_product_part_id');
    }
}
