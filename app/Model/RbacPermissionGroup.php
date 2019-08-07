<?php

namespace App\Model;

use Encore\Admin\Auth\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RbacPermissionGroup extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(RbacPermission::class,'rbac_permission_group_id','id');
    }
}
