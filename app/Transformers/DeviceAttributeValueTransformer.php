<?php

namespace App\Transformers;

use App\Model\DeviceAttributeKey;
use App\Model\DeviceAttributeValue;
use League\Fractal\TransformerAbstract;

class DeviceAttributeValueTransformer extends TransformerAbstract
{
    public function transform(DeviceAttributeValue $deviceAttributeValue)
    {
        return $deviceAttributeValue->toArray();
    }
}
