<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProductPlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'warehouse_product_id',
        'warehouse_product_part_id',
        'started_at',
        'explain',
        'last_processor_id',
        'last_processed_at'
    ];

    public function warehouseProductInstance()
    {
        return $this->hasOne(WarehouseProductInstance::class, 'id', 'warehouse_product_instance_id');
    }

    public function warehouseProductPart()
    {
        return $this->hasOne(WarehouseProductPart::class, 'id', 'warehouse_product_part_id');
    }

    public function lastProcessor()
    {
        return $this->hasOne(Account::class, 'id', 'last_processor_id');
    }
}
