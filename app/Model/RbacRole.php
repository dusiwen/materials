<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RbacRole extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function accounts()
    {
        return $this->belongsToMany(Account::class,'pivot_role_accounts','rbac_role_id','account_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(RbacPermission::class,'pivot_role_permissions','rbac_role_id','rbac_permission_id');
    }

    public function menus()
    {
        return $this->belongsToMany(RbacRole::class,'pivot_role_menus','rbac_role_id','rbac_menu_id');
    }
}
