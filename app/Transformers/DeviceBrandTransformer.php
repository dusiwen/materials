<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\DeviceBrand;
use League\Fractal\TransformerAbstract;

class DeviceBrandTransformer extends TransformerAbstract
{
    public function transform(DeviceBrand $deviceBrand)
    {
        return $deviceBrand->toArray();
    }
}
