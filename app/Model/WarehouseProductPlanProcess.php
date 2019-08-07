<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProductPlanProcess extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'warehouse_product_plan_id',
        'processor_id',
        'processed_at',
    ];

    public function warehouseProductPlan()
    {
        return $this->hasOne(WarehouseProductPlan::class, 'id', 'warehouse_product_plan_id');
    }

    public function processor()
    {
        return $this->hasOne(Account::class, 'id', 'processor_id');
    }
}
