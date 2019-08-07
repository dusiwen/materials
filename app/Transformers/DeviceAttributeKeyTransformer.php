<?php

namespace App\Transformers;

use App\Model\DeviceAttributeKey;
use League\Fractal\TransformerAbstract;

class DeviceAttributeKeyTransformer extends TransformerAbstract
{
    public function transform(DeviceAttributeKey $deviceAttributeKey)
    {
        return $deviceAttributeKey->toArray();
    }
}
