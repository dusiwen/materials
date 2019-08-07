<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measurement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identity_code',
        'entire_model_unique_code',
        'part_model_unique_code',
        'key',
        'allow_min',
        'allow_max',
        'allow_explain',
        'unit',
        'operation',
        'explain',
        'character'
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
