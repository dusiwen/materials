<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProductInstanceLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'operator_id',
        'warehouse_product_instance_open_code',
    ];

    public function warehouseProductInstance()
    {
        return $this->hasOne(WarehouseProductInstance::class, 'open_code', 'warehouse_product_instance_open_code');
    }
}
