<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceSkuRequest extends FormRequest
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
            'spu_id' => 'required|integer|min:1',
            'name' => 'required|between:2,191',
            'attribute_code' => 'required|between:1,191',
            'template_id' => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'spu_id.required' => 'SPU编号不能为空',
            'spu_id.integer' => 'SPU编号必须是整数',
            'spu_id.min' => 'SPU编号不能小于1',
            'name.required' => '名称不能为空',
            'name.between' => '名称必须大于2位或小于191位',
            'attribute_code.required' => '属性组合码不能为空',
            'attribute_code.between' => '属性组合码名称必须大于2位或小于191位',
            'template_id.integer' => '模板编号必须是整数',
            'template_id.min' => '模板编号不能小于1',
        ];
    }
}
