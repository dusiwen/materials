<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixWorkflow extends Model
{
    use  SoftDeletes;

    public static $TYPE = [
        'FIX' => '检修单',
        'CHECK' => '验收单',
    ];

    public static $STATUS = [
        'UNFIX' => '待处理',
        'IN_WAREHOUSE' => '入库检测',
        'FIX_BEFORE' => '修前检',
        'FIX_AFTER' => '修后检',
        'CHECKED' => '验收员验收',
        'WORKSHOP' => '车间抽验',
        'SECTION' => '工区验收',
        'FIXED' => '检修完成',
        'RETURN_FACTORY' => '返厂维修',
        'FACTORY_RETURN' => '返厂入所',
        'FIXING' => '检修中',
    ];

    public static $STAGE = [
        'UNFIX' => '等待检修',
        'PART' => '部件检测',
        'ENTIRE' => '整件检测',
        'RETURN_FACTORY' => '返厂维修',
        'FACTORY_RETURN' => '返厂回所',
        'FIXED' => '检修完成',
        'CHECKED' => '工区验收',
        'WORKSHOP' => '车间抽验',
        'SECTION' => '段技术科抽验',
        'FIX_BEFORE' => '修前检',
        'FIX_AFTER' => '修后检',
        'WAIT_CHECK' => '等待验收',
    ];

    protected $fillable = [
        'entire_instance_identity_code',
        'warehouse_report_serial_number',
        'status',
        'processor_id',
        'expired_at',
        'id_by_failed',
        'serial_number',
        'note',
        'processed_times',
        'stage',
        'maintain_station_name',
        'maintain_location_code',
        'is_cycle',
        'entire_fix_after_count',
        'part_fix_after_count',
        'type',
        'check_serial_number',
    ];

    public static function flipStatus($value)
    {
        return array_flip(self::$STATUS)[$value];
    }

    public static function flipStage($value)
    {
        return array_flip(self::$STAGE)[$value];
    }

    public static function flipType(string $key)
    {
        return array_flip(self::$TYPE)[$key];
    }

    public function getStageAttribute($value)
    {
        return self::$STAGE[$value];
    }

    public function prototype($attributeKey)
    {
        return $this->attributes[$attributeKey];
    }

    public function Processor()
    {
        return $this->hasOne(Account::class, 'id', 'processor_id');
    }

    public function EntireInstance()
    {
        return $this->hasOne(EntireInstance::class, 'identity_code', 'entire_instance_identity_code');
    }

    public function FixWorkflowProcesses()
    {
        return $this->hasMany(FixWorkflowProcess::class, 'fix_workflow_serial_number', 'serial_number');
    }

    public function WarehouseReport()
    {
        return $this->hasOne(WarehouseReport::class, 'serial_number', 'warehouse_report_serial_number');
    }

    public function getStatusAttribute($value)
    {
        return self::$STATUS[$value];
    }

    public function getTypeAttribute(string $value)
    {
        return self::$TYPE[$value];
    }

    public function CheckFixWorkflow()
    {
        return $this->hasOne(FixWorkflow::class, 'check_serial_number', 'check_serial_number');
    }
}
