<?php

namespace App\Transformers;

use App\Model\Account;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    public function transform(Account $account)
    {
        return $account->toArray();
    }
}
