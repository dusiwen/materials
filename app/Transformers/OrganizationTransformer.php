<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\Organization;
use League\Fractal\TransformerAbstract;

class OrganizationTransformer extends TransformerAbstract
{
    public function transform(Organization $organization)
    {
        return $organization->toArray();
    }
}
