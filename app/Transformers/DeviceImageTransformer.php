<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\DeviceBrand;
use App\Model\DeviceImage;
use League\Fractal\TransformerAbstract;

class DeviceImageTransformer extends TransformerAbstract
{
    public function transform(DeviceImage $deviceImage)
    {
        return $deviceImage->toArray();
    }
}
