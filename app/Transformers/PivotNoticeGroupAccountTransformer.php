<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\NoticeGroup;
use App\Model\PivotNoticeGroupAccount;
use League\Fractal\TransformerAbstract;

class PivotNoticeGroupAccountTransformer extends TransformerAbstract
{
    public function transform(PivotNoticeGroupAccount $pivotNoticeGroupAccount)
    {
        return $pivotNoticeGroupAccount->toArray();
    }
}
