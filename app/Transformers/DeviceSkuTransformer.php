<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\DeviceBrand;
use App\Model\DeviceImage;
use App\Model\DeviceSku;
use App\Model\DeviceSpu;
use League\Fractal\TransformerAbstract;

class DeviceSkuTransformer extends TransformerAbstract
{
    public function transform(DeviceSku $deviceSku)
    {
        return $deviceSku->toArray();
    }
}
