<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\NoticeGroup;
use League\Fractal\TransformerAbstract;

class NoticeGroupTransformer extends TransformerAbstract
{
    public function transform(NoticeGroup $noticeGroup)
    {
        return $noticeGroup->toArray();
    }
}
