<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\PivotRoleAccount;
use App\Model\PivotRoleMenu;
use App\Model\RbacMenu;
use App\Model\RbacPermission;
use League\Fractal\TransformerAbstract;

class PivotRoleMenuTransformer extends TransformerAbstract
{
    public function transform(PivotRoleMenu $pivotRoleMenu)
    {
        return $pivotRoleMenu->toArray();
    }
}
