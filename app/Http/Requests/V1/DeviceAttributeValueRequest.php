<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceAttributeValueRequest extends FormRequest
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
            'attribute_key_id' => 'required|integer|min:1',
            'category_id' => 'required|integer|min:1',
            'sort' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'名称不能为空',
            'name.between'=>'名称不能小于2位或大于191位',
            'attribute_key_id.required'=>'属性键编号不能为空',
            'attribute_key_id.integer'=>'属性键编号必须是整数',
            'attribute_key_id.min'=>'属性键编号不能小于1',
            'category_id.required'=>'类目编号不能为空',
            'category_id.integer'=>'类目编号必须是整数',
            'category_id.min'=>'类目编号不能小于1',
            'sort.integer'=>'排序依据必须是整数',
            'sort.min'=>'排序依据不能小于0',
        ];
    }
}
