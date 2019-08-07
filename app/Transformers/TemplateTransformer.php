<?php

namespace App\Transformers;

use App\Model\Device;
use App\Model\DeviceAttributeKey;
use App\Model\Template;
use League\Fractal\TransformerAbstract;

class TemplateTransformer extends TransformerAbstract
{
    public function transform(Template $template)
    {
        $template = $template->toArray();
        $template['format'] = json_decode($template['format'],true);
        return $template;
    }
}
