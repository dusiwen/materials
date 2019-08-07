<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AlarmTemplateDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'alarm_template_id' => 'nullable|integer|min:1',
            'device_open_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'alarm_template_id.integer' => '模板编号必须需是整数',
            'alarm_template_id.ming' => '模板编号不能小于1',
            'device_open_code.required' => '设备唯一标识不能为空'
        ];
    }
}
