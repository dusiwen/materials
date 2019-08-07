<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\RbacMenu;
use App\Model\RbacPermission;
use League\Fractal\TransformerAbstract;

class RbacMenuTransformer extends TransformerAbstract
{
    public function transform(RbacMenu $rbacMenu)
    {
        return $rbacMenu->toArray();
    }
}
