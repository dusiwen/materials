<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Race extends Model
{
    use SoftDeletes;

    public function Categories()
    {
        return $this->hasMany(Category::class, 'race_unique_code', 'unique_code');
    }
}
