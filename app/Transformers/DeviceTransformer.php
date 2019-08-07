<?php

namespace App\Transformers;

use App\Model\Device;
use League\Fractal\TransformerAbstract;

class DeviceTransformer extends TransformerAbstract
{
    public function transform(Device $device)
    {
        return $device->toArray();
    }
}
