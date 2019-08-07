<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntireInstance extends Model
{
    use SoftDeletes;

    public static $STATUS = [
        'BUY_IN' => '新入所',
        'INSTALLING' => '安装中',
        'INSTALLED' => '安装完成',
        'FIXING' => '检修中',
        'FIXED' => '可用',
        'RETURN_FACTORY' => '返厂维修',
        'FACTORY_RETURN' => '返厂入所',
        'SCRAP' => '报废',
    ];
    public static $FIX_CYCLE_UNIT = [
        'YEAR' => '年',
        'MONTH' => '月',
        'WEEK' => '周',
        'DAY' => '日',
    ];
    protected $fillable = [
        'entire_model_unique_code',
        'entire_model_id_code',
        'serial_number',
        'status',
        'maintain_station_name',
        'maintain_location_code',
        'is_main',
        'factory_name',
        'factory_device_code',
        'identity_code',
        'last_installed_time',
        'in_warehouse',
        'category_name',
        'category_unique_code',
        'fix_workflow_serial_number',
        'last_warehouse_report_serial_number_by_out',
        'is_flush_serial_number',
        'next_auto_making_fix_workflow_time',
        'next_fixing_time',
        'next_auto_making_fix_workflow_at',
        'next_fixing_month',
        'next_fixing_day',
        'fix_cycle_unit',
        'fix_cycle_value',
        'cycle_fix_count',
        'un_cycle_fix_count',
        'old_number',
        'purpose',
        'warehouse_name',
        'warehouse_location',
        'to_direction',
        'crossroad_number',
        'traction',
        'source',
        'source_crossroad_number',
        'source_traction',
        'forecast_install_at',
        'line_unique_code',
        'line_name',
        'open_direction',
        'said_rod',
        'scarped_note',
        'railway_name',
        'section_name',
        'base_name',
    ];

    public static function flipFixCycleUnit($value)
    {
        return array_flip(self::$FIX_CYCLE_UNIT)[$value];
    }

    public function prototype(string $attributeKey)
    {
        return $this->attributes[$attributeKey];
    }

    public function getStatusAttribute($value)
    {
        return self::$STATUS[$value];
    }

    public function MaintainStation()
    {
        return $this->hasOne(Maintain::class, 'station_name', 'maintain_station_name');
    }

    public function MaintainLocation()
    {
        return $this->hasOne(Maintain::class, 'location_code', 'maintain_location_code');
    }

    public function EntireModel()
    {
        return $this->hasOne(EntireModel::class, 'unique_code', 'entire_model_unique_code');
    }

    public function Category()
    {
        return $this->hasOne(Category::class, 'unique_code', 'category_unique_code');
    }

    public function PartInstances()
    {
        return $this->hasMany(PartInstance::class, 'entire_instance_identity_code', 'identity_code');
    }

    public function FixWorkflow()
    {
        return $this->hasOne(FixWorkflow::class, 'serial_number', 'fix_workflow_serial_number');
    }

    public function FixWorkflows()
    {
        return $this->hasMany(FixWorkflow::class, 'entire_instance_identity_code', 'identity_code');
    }

    public function Measurements()
    {
        return $this->hasMany(Measurement::class, 'entire_model_unique_code', 'entire_model_unique_code');
    }

    public function WarehouseReportByOut()
    {
        return $this->hasOne(WarehouseReport::class, 'serial_number', 'last_warehouse_report_serial_number_by_out');
    }

    public function EntireModelIdCode()
    {
        return $this->hasOne(EntireModelIdCode::class, 'code', 'entire_model_id_code');
    }
}
