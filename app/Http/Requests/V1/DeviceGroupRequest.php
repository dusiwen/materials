<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceGroupRequest extends FormRequest
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
            'name' => 'required|between:2,191',
            'organization_id' => 'required|integer|min:1',
//            'alarm_template_id' => 'required|integer|min:1',
            'line_id' => 'nullable|integer|min:1',
            'longitude' => 'required',
            'latitude' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名称不能为空',
            'name.between' => '名称不能小于2位或大于191位',
            'organization_id.required' => '机构编号不能为空',
            'organization_id.integer' => '机构编号必须是整数',
            'organization_id.min' => '机构编号不能小于1',
//            'alarm_template_id.required' => '报警模版编号不能为空',
//            'alarm_template_id.integer' => '报警模版编号必须是整数',
//            'alarm_template_id.min' => '报警模版编号编号不能小于1',
            'line_id.integer' => '所属线路编号必须是整数',
            'line_id.min' => '所属线路编号不能小于1',
            'longitude.required' => '经度不能为空',
            'latitude.required' => '纬度不能为空',
        ];
    }
}
