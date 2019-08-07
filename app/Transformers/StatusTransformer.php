<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\Status;
use League\Fractal\TransformerAbstract;

class StatusTransformer extends TransformerAbstract
{
    public function transform(Status $status)
    {
        return $status->toArray();
    }
}
