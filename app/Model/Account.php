<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'account',
        'password',
        'nickname',
        'email',
        'phone',
        'status_id',
        'open_id',
        'organization_id',
        'email_code',
        'email_code_exp',
        'wechat_official_open_id',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(
            RbacRole::class,
            'pivot_role_accounts',
            'account_id',
            'rbac_role_id'
        );
    }
}
