<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintain extends Model
{
    use SoftDeletes;

    public static $TYPE = [
        'WORKSHOP' => '车间',
        'STATION' => '站',
    ];

    protected $fillable = [
        'unique_code',
        'name',
        'location_code',
        'explain',
        'parent_unique_code',
        'type',
    ];

    public function prototype($attributeKey)
    {
        return $this->attributes[$attributeKey];
    }

    public function getTypeAttribute($value)
    {
        return self::$TYPE[$value];
    }

    public function EntireInstances()
    {
        return $this->hasMany(EntireInstance::class, 'maintain_identity_code', 'identity_code');
    }

    public function Parent()
    {
        return $this->hasOne(Maintain::class, 'unique_code', 'parent_unique_code');
    }

    public function Subs()
    {
        return $this->hasMany(Maintain::class, 'parent_unique_code', 'unique_code');
    }
}
