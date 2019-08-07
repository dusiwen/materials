<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PivotRolePermission extends Model
{
    protected $fillable = ['rbac_role_id', 'rbac_permission_id'];

    public function role()
    {
        return $this->hasOne(RbacRole::class, 'id', 'rbac_role_id');
    }

    public function permission()
    {
        return $this->hasOne(RbacPermission::class, 'id', 'rbac_permission_id');
    }
}
