<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartModel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'unique_code',
        'category_unique_code',
    ];

    public function Category()
    {
        return $this->hasOne(Category::class, 'unique_code', 'category_unique_code');
    }

    public function EntireModels()
    {
        return $this->belongsToMany(
            'App\Model\EntireModel',
            'pivot_entire_model_and_part_models',
            'part_model_unique_code',
            'entire_model_unique_code'
        );
    }

    public function Measurements()
    {
        return $this->hasMany(Measurement::class, 'part_model_unique_code', 'unique_code');
    }
}
