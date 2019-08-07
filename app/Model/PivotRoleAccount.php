<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PivotRoleAccount extends Model
{
    protected $fillable = ['rbac_role_id', 'account_id'];

    public function roles()
    {
        return $this->belongsTo(RbacRole::classs, 'rbac_role_id', 'id');
    }

    public function accounts()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }
}
