<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceSpuRequest extends FormRequest
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
            'name' => 'required|between:2,50',
            'brand_id' => 'nullable|integer|min:1',
            'avatar_image_id' => 'nullable|integer|min:1',
            'device_category_id' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名称不能为空',
            'name.between' => '名称必须小于2位或大于50位',
            'brand_id.integer' => '品牌编号必须是整数',
            'brand_id.min' => '品牌编号不能小于1',
            'avatar_image_id.integer' => '封面图编号必须是整数',
            'avatar_image_id.min' => '封面图编号不能小于1',
            'device_category_id.required'=>'所属类目编号不能为空',
            'device_category_id.integer'=>'所属类目编号必须是整数',
            'device_category_id.min'=>'所属类目编号不能小于1',
        ];
    }
}
