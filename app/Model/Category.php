<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'unique_code'];

    /**
     * 该类目下所有实例
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function EntireModels()
    {
        return $this->hasMany(EntireModel::class, 'category_unique_code', 'unique_code');
    }

    public function Race()
    {
        return $this->hasOne(Race::class, 'unique_code', 'race_unique_code');
    }
}
