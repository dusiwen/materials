<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntireInstanceCount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entire_model_unique_code',
        'year',
        'count',
    ];

    public function EntireModel()
    {
        return $this->hasOne(EntireModel::class, 'unique_code', 'entire_model_unique_code');
    }
}
