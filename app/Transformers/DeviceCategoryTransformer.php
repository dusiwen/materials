<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\DeviceBrand;
use App\Model\DeviceCategory;
use League\Fractal\TransformerAbstract;

class DeviceCategoryTransformer extends TransformerAbstract
{
    public function transform(DeviceCategory $deviceCategory)
    {
        return $deviceCategory->toArray();
    }
}
