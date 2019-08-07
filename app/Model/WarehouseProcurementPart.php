<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProcurementPart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'serial_number',
        'processor_id',
        'processed_at',
    ];

    public function warehouseProcurementPartInstances()
    {
        return $this->hasMany(WarehouseProcurementPartInstance::class, 'warehouse_procurement_part_id', 'id');
    }

    public function warehouseReportProductParts()
    {
        return $this->hasMany(WarehouseReportProductPart::class, 'warehouse_procurement_part_id', 'id');
    }

    public function processor()
    {
        return $this->hasOne(Account::class, 'id', 'processor_id');
    }
}
