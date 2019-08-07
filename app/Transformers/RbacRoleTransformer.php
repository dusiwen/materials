<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\RbacMenu;
use App\Model\RbacPermission;
use App\Model\RbacRole;
use League\Fractal\TransformerAbstract;

class RbacRoleTransformer extends TransformerAbstract
{
    public function transform(RbacRole $rbacRole)
    {
        return $rbacRole->toArray();
    }
}
