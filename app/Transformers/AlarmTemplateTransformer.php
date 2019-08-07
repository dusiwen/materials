<?php

namespace App\Transformers;

use App\Model\AlarmTemplate;
use League\Fractal\TransformerAbstract;

class AlarmTemplateTransformer extends TransformerAbstract
{
    public function transform(AlarmTemplate $alarmTemplate)
    {

        $alarmTemplate = $alarmTemplate->toArray();
        $content = json_decode($alarmTemplate['content'],true)['email'];
        $contentVar = json_decode($alarmTemplate['content_var'],true)['email'];
        $alarmTemplate['content_complete'] = vsprintf($content, $contentVar);
        return $alarmTemplate;
    }
}
