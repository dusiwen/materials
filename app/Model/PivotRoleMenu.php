<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PivotRoleMenu extends Model
{
    protected $fillable = ['rbac_role_id', 'rbac_menu_id'];

    public function role()
    {
        return $this->hasOne(RbacRole::class, 'id', 'rbac_role_id');
    }

    public function menu()
    {
        return $this->hasOne(RbacMenu::class, 'id', 'rbac_menu_id');
    }
}
