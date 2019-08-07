<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PivotEntireModelAndPartModel extends Model
{
    protected $fillable = [
        'entire_model_unique_code',
        'part_model_unique_code',
        'number'
    ];

    public function EntireModel()
    {
        return $this->hasOne(EntireModel::class, 'unique_code', 'entire_model_unique_code');
    }

    public function PartModel()
    {
        return $this->hasOne(PartModel::class, 'unique_code', 'part_model_unique_code');
    }
}
