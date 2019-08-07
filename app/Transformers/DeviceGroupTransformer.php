<?php

namespace App\Transformers;

use App\Model\Device;
use App\Model\DeviceGroup;
use League\Fractal\TransformerAbstract;

class DeviceGroupTransformer extends TransformerAbstract
{
    public function transform(DeviceGroup $deviceGroup)
    {
        return $deviceGroup->toArray();
    }
}
