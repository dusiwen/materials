<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
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
            'sort' => 'nullable|integer|min:0',
            'organization_id' => 'required|integer|min:1',
            'spu_id' => 'required|integer|min:1',
            'sku_id' => 'required|integer|min:1',
            'device_group_id' => 'nullable|integer|min:1',
            'is_group' => 'nullable|integer|in:0,1',
            'open_code' => 'required|unique:devices|between:2,191',
        ];
    }

    public function messages()
    {
        return [
            'organization_id.integer' => '机构编号必须是整数',
            'organization_id.min' => '机构编号不能小于1',
            'spu_id.required' => '请选择SPU',
            'spu_id.integer' => 'SPU编号必须是整数',
            'spu_id.min' => 'SPU编号不能小于1',
            'sku_id.required' => '请选择SKU',
            'sku_id.integer' => 'SKU编号必须是整数',
            'sku_id.min' => 'SKU编号不能小于1',
            'device_group_id.integer' => '设备分组编号必须是整数',
            'device_group_id.min' => '设备分组编号不能小于1',
            'is_group.integer' => '集群标记必须是整数',
            'is_group.in' => '集群标记必须是0或1',
            'open_code.required'=>'开放编号不能为空',
            'open_code.unique'=>'开放编号已被占用',
            'open_code.between'=>'开放编号不能小于2位或大于191位',
        ];
    }
}
