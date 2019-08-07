<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RbacMenu extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'parent_id', 'sort', 'icon', 'uri', 'sub_title','action_as'];

    public function roles()
    {
        return $this->belongsToMany(RbacRole::class, 'pivot_role_menus', 'rbac_menu_id', 'rbac_role_id');
    }

    public function parent()
    {
        return $this->hasOne(RbacMenu::class, 'id', 'parent_id');
    }
}
