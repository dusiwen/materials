<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PivotFromWarehouseProductToWarehouseProductPart extends Model
{
    protected $fillable = ['warehouse_product_id', 'warehouse_product_part_id'];

    public function warehouseProduct()
    {
        return $this->hasOne(WarehouseProduct::class, 'id', 'warehouse_product_id');
    }

    public function warehouseProductPart()
    {
        return $this->hasOne(WarehouseProductPart::class, 'id', 'warehouse_product_part_id');
    }
}
