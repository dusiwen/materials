<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UnCycleFixReport extends Model
{
    protected $fillable = [
        'entire_instance_identity_code',
        'part_instance_identity_code',
        'fix_workflow_serial_number',
    ];

    public function EntireInstance()
    {
        return $this->hasOne(EntireInstance::class, 'identity_code', 'entire_instance_identity_code');
    }

    public function PartInstance()
    {
        return $this->hasOne(PartInstance::class, 'identity_code', 'part_instance_identity_code');
    }

    public function FixWorkflow()
    {
        return $this->hasOne(FixWorkflow::class, 'serial_number', 'fix_workflow_serial_number');
    }
}
