<?php

namespace App\Transformers;

use App\Model\Account;
use App\Model\DeviceAttribute;
use App\Model\DeviceBrand;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\Fractal\TransformerAbstract;

class DeviceAttributeTransformer extends TransformerAbstract
{
    public function transform(DeviceAttribute $deviceAttribute)
    {
        return $deviceAttribute->toArray();
    }
}
