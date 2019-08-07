<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixWorkflowProcessPart extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'fix_workflow_process_serial_number',
        'part_instance_identity_code',
        'note',
        'measurement_identity_code',
        'measured_value',
        'processor_id',
        'processed_at',
    ];

    public function Processor()
    {
        return $this->hasOne(Account::class,'id','processor_id');
    }

    public function FixWorkflowProcess()
    {
        return $this->hasOne(FixWorkflowProcess::class,'serial_number','fix_workflow_process_serial_number');
    }

    public function PartInstance()
    {
        return $this->hasOne(PartInstance::class,'identity_code','part_instance_identity_code');
    }

    public function Measurement()
    {
        return $this->hasOne(Measurement::class,'identity_code','measurement_identity_code');
    }
}
