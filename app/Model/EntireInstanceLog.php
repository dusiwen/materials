<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntireInstanceLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'entire_instance_identity_code',
    ];

    public function EntireInstance()
    {
        return $this->hasOne(EntireInstance::class,'identity_code','entire_instance_identity_code');
    }
}
