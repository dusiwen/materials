<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WarehouseBatchReport extends Model
{
    protected $fillable = [
        'entire_instance_identity_code',
        'factory_device_code',
    ];

    public function EntireInstance()
    {
        return $this->hasOne(EntireInstance::class, 'identity_code', 'entire_instance_identity_code');
    }
}
