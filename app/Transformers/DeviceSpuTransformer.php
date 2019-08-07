<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\DeviceBrand;
use App\Model\DeviceImage;
use App\Model\DeviceSpu;
use League\Fractal\TransformerAbstract;

class DeviceSpuTransformer extends TransformerAbstract
{
    public function transform(DeviceSpu $deviceSpu)
    {
        return $deviceSpu->toArray();
    }
}
