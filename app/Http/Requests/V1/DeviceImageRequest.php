<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceImageRequest extends FormRequest
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
            'name' => 'nullable|between:2,50',
            'description' => 'nullable|between:2,191',
            'spu_id' => 'required|integer|min:1',
            'sort' => 'nullable|integer|min:0',
            'position' => 'nullable|in:GROUP,DESCRIPTION,SKU_BUTTON',
        ];
    }

    public function messages()
    {
        return [
            'name.between' => '名称不能小于2位或大于50位',
            'description.between' => '描述不能小于2位或大于191位',
            'spu_id.integer' => 'SPU编号必须是整数',
            'spu_id.min' => 'SPU编号不能小于1',
            'src.between' => '图片地址不能小于2位或大于191位',
            'sort.integer' => '排序依据必须是整数',
            'sort.min' => '排序依据不能小于1',
            'position.in' => '图片位置必须是：GROUP、DESCRIPTION、SKU_BUTTON',
        ];
    }
}
