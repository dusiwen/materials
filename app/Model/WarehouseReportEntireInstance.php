<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReportEntireInstance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'warehouse_report_serial_number',
        'entire_instance_identity_code',
    ];

    public function WarehouseReport()
    {
        return $this->hasOne(WarehouseReport::class,'serial_number','warehouse_report_serial_number');
    }

    public function EntireInstance()
    {
        return $this->hasOne(EntireInstance::class,'identity_code','entire_instance_identity_code');
    }
}
