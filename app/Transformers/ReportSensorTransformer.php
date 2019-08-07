<?php

namespace App\Transformers;

use App\Model\ReportSensor;
use League\Fractal\TransformerAbstract;

class ReportSensorTransformer extends TransformerAbstract
{
    public function transform(ReportSensor $reportSensor)
    {
        return $reportSensor->toArray();
    }
}
