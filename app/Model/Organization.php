<?php

namespace App\Model;

use App\Facades\OrganizationLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'parent_id', 'level', 'db_conn_str', 'is_main'];

    public static function getDeepBySession()
    {
        return self::whereIn('id',OrganizationLevel::getDeepBySession())->orderByDesc('id')->get();
    }

    public static function pagniateDeepBySession()
    {
        return self::whereIn('id',OrganizationLevel::getDeepBySession())->orderByDesc('id')->pagniate();
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'organization_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(Organization::class, 'id', 'parent_id');
    }
}
