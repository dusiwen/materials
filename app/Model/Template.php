<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'position', 'style', 'level', 'condition_type', 'condition_value', 'format', 'device_category_id'];

    public function deviceCategory()
    {
        return $this->hasOne(DeviceCategory::class, 'id', 'device_category_id');
    }

    protected function getFormatAttribute($value)
    {
        return json_decode($value, true);
    }
}
