<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixWorkflowProcess extends Model
{
    use SoftDeletes;

    public static $STAGE = [
        'FIX_BEFORE' => '修前检',
        'FIX_AFTER' => '修后检',
        'CHECKED' => '工区验收',
        'WORKSHOP' => '车间抽验',
        'SECTION' => '段技术科抽验',
    ];
    public static $TYPE = [
        'ENTIRE' => '整件检修',
        'PART' => '部件检修'
    ];
    protected $fillable = [
        'fix_workflow_serial_number',
        'note',
        'measured_value',
        'stage',
        'type',
        'serial_number',
        'auto_explain',
        'entire_instance_identity_code',
        'part_instance_identity_code',
        'numerical_order',
        'is_allow',
        'processor_id',
        'processed_at'
    ];

    public function EntireInstance()
    {
        return $this->hasOne(EntireInstance::class, 'identity_code', 'entire_instance_identity_code');
    }

    public function PartInstance()
    {
        return $this->hasOne(PartInstance::class, 'identity_code', 'part_instance_identity_code');
    }

    public function prototype($attributeKey)
    {
        return $this->attributes[$attributeKey];
    }

    public function getStageAttribute($value)
    {
        return self::$STAGE[$value];
    }

    public function prototypeStageAttribute($value = null)
    {
        return array_flip(self::$STAGE)[$value ?: $this->attributes['stage']];
    }

    public function FixWorkflow()
    {
        return $this->hasOne(FixWorkflow::class, 'serial_number', 'fix_workflow_serial_number');
    }

    public function Measurement()
    {
        return $this->hasOne(Measurement::class, 'identity_code', 'measurement_identity_code');
    }

    public function Processor()
    {
        return $this->hasOne(Account::class, 'id', 'processor_id');
    }

    public function getTypeAttribute($value)
    {
        return self::$TYPE[$value];
    }

    public function FixWorkflowRecords()
    {
        return $this->hasMany(FixWorkflowRecord::class, 'fix_workflow_process_serial_number', 'serial_number');
    }
}
