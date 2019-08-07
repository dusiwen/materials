<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'official_home_link',
        'unique_code',
    ];

    public function EntireInstances()
    {
        return $this->hasMany(EntireInstance::class,"factory_name","name");
    }
}
