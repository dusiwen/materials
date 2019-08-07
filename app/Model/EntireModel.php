<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntireModel extends Model
{
    use SoftDeletes;

    public static $FIX_CYCLE_UNIT = [
        'YEAR' => '年',
        'MONTH' => '月',
        'WEEK' => '周',
        'DAY' => '日',
    ];

    protected $fillable = [
        'name',
        'unique_code',
        'category_unique_code',
        'fix_cycle_unit',
        'fix_cycle_value',
    ];

    public static function flipFixCycleUnit($value)
    {
        return array_flip(self::$FIX_CYCLE_UNIT)[$value];
    }

    public function prototype($attributeKey)
    {
        return $this->attributes[$attributeKey];
    }

    public function getFixCycleUnitAttribute($value)
    {
        return self::$FIX_CYCLE_UNIT[$value];
    }

    public function Category()
    {
        return $this->hasOne(Category::class, 'unique_code', 'category_unique_code');
    }

    public function EntireInstances()
    {
        return $this->hasMany(EntireInstance::class, 'entire_model_unique_code', 'unique_code');
    }

    public function Measurements()
    {
        return $this->hasMany(Measurement::class, 'entire_model_unique_code', 'unique_code');
    }

    public function PartModels()
    {
        return $this->belongsToMany(
            'App\Model\PartModel',
            'pivot_entire_model_and_part_models',
            'entire_model_unique_code',
            'part_model_unique_code'
        );
    }

    public function EntireModelIdCodes()
    {
        return $this->hasMany(EntireModelIdCode::class, 'entire_model_unique_code', 'unique_code');
    }
}
