<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReportInProductInstance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'warehouse_report_in_order_serial_number',
        'warehouse_product_instance_open_code',
        'factory_unique_code',
        'factory_product_instance_open_code',
    ];

    public function warehouseReportInOrder()
    {
        return $this->hasOne(WarehouseReportInOrder::class, 'serial_number', 'warehouse_report_in_order_serial_number');
    }

    public function warehouseProductInstance()
    {
        return $this->hasOne(WarehouseProductInstance::class,'open_code','warehouse_product_instance_open_code');
    }

    public function factory()
    {
        return $this->hasOne(Factory::class,'unique_code','factory_unique_code');
    }
}
