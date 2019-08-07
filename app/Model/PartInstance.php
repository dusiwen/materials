<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartInstance extends Model
{
    use SoftDeletes;

    public static $STATUS = [
        'BUY_IN' => '采购入所',
        'INSTALLING' => '安装中',
        'INSTALLED' => '安装完成',
        'FIXING' => '检修中',
        'FIXED' => '检修完成',
        'RETURN_FACTORY' => '返厂维修',
        'FACTORY_RETURN' => '返厂入所',
        'SCRAP' => '报废',
    ];

    protected $fillable = [
        'part_model_unique_code',
        'entire_instance_identity_code',
        'status',
        'factory_name',
        'factory_device_code',
        'identity_code',
        'entire_instance_serial_number'
    ];

    public function flipStatus()
    {
        return self::$STATUS[$this->value];
    }

    public function EntireInstance()
    {
        return $this->hasOne(EntireInstance::class, 'identity_code', 'entire_instance_identity_code');
    }

    public function PartModel()
    {
        return $this->hasOne(PartModel::class, 'unique_code', 'part_model_unique_code');
    }

    public function getStatusAttribute($value)
    {
        return self::$STATUS[$value];
    }
}
