<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\PivotRoleAccount;
use App\Model\RbacMenu;
use App\Model\RbacPermission;
use League\Fractal\TransformerAbstract;

class PivotRoleAccountTransformer extends TransformerAbstract
{
    public function transform(PivotRoleAccount $pivotRoleAccount)
    {
        return $pivotRoleAccount->toArray();
    }
}
