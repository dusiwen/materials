<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EntireModelIdCode extends Model
{
    protected $fillable = [
        'category_unique_code',
        'entire_model_unique_code',
        'code',
        'name',
    ];

    public function EntireModel()
    {
        return $this->hasOne(EntireModel::class, 'unique_code', 'entire_model_unique_code');
    }

    public function EntireInstance()
    {
        return $this->hasMany(EntireInstance::class, 'entire_model_id_code', 'code');
    }
}
