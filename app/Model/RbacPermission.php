<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RbacPermission extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'http_method', 'http_path', 'rbac_permission_group_id'];

    public function roles()
    {
        return $this->belongsToMany(RbacRole::class, 'pivot_role_permissions', 'rbac_role_id', 'rbac_permission_id');
    }

    public function permissionGroup()
    {
        return $this->hasOne(RbacPermissionGroup::class, 'id', 'rbac_permission_group_id');
    }
}
