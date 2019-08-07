<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\NoticeGroup;
use App\Model\PivotNoticeGroupAccount;
use App\Model\PivotNoticeGroupDevice;
use League\Fractal\TransformerAbstract;

class PivotNoticeGroupDeviceTransformer extends TransformerAbstract
{
    public function transform(PivotNoticeGroupDevice $pivotNoticeGroupDevice)
    {
        return $pivotNoticeGroupDevice->toArray();
    }
}
