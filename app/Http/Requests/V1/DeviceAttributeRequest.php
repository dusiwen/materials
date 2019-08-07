<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceAttributeRequest extends FormRequest
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
            'attribute_key_id' => 'required|integer|min:1',
            'attribute_value_id' => 'required|integer|min:1',
            'spu_id' => 'required|integer|min:1',
            'sku_id' => 'nullable|integer|min:1',
            'button_image_id' => 'nullable|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'attribute_key_id.required' => '属性键编号不可为空',
            'attribute_key_id.integer' => '属性键编号必须是整数',
            'attribute_key_id.min' => '属性键编号不能小于1',
            'attribute_value_id.required' => '属性值编号不可为空',
            'attribute_value_id.integer' => '属性值编号必须是整数',
            'attribute_value_id.min' => '属性值编号不能小于1',
            'spu_id.required' => 'SPU编号不可为空',
            'spu_id.integer' => 'SPU编号必须是整数',
            'spu_id.min' => 'SPU编号不能小于1',
            'sku_id.integer' => 'SKU编号必须是整数',
            'sku_id.min' => 'SKU编号不能小于1',
            'button_image_id.required' => '按钮图片编号不可为空',
            'button_image_id.integer' => '按钮图片编号必须是整数',
            'button_image_id.min' => '按钮图片编号不能小于1',
        ];
    }
}
