<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    public function accounts()
    {
        return $this->hasMany(Account::class, 'status_id', 'id');
    }
}
