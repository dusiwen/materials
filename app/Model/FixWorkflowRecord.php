<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use test\Mockery\SimpleTrait;

class FixWorkflowRecord extends Model
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
        'type',
        'is_allow',
    ];

    public function Processor()
    {
        return $this->hasOne(Account::class,'id','processor_id');
    }

    public function FixWorkflowProcess()
    {
        return $this->hasOne(FixWorkflowProcess::class,'serial_number','fix_workflow_process_serial_number');
    }

    public function EntireInstance()
    {
        return $this->hasOne(EntireInstance::class,'identity_code','entire_instance_identity_code');
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
