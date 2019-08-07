<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\PivotRoleAccount;
use App\Model\PivotRolePermission;
use App\Model\RbacMenu;
use App\Model\RbacPermission;
use League\Fractal\TransformerAbstract;

class PivotRolePermissionTransformer extends TransformerAbstract
{
    public function transform(PivotRolePermission $pivotRolePermission)
    {
        return $pivotRolePermission->toArray();
    }
}
