<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProductCount extends Model
{
    use SoftDeletes;

    protected $fillable = ['warehouse_product_id', 'year', 'count'];

    public function warehouseProduct()
    {
        return $this->hasOne(WarehouseProduct::class, 'id', 'warehouse_product_id');
    }
}
