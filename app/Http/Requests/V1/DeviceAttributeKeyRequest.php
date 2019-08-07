<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceAttributeKeyRequest extends FormRequest
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
            'category_id' => 'required|integer|min:1',
            'is_must' => 'nullable|in:0,1',
            'sort' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名称不能你为空',
            'name.between' => '名称必须大于2位或小于50位',
            'category_id.required' => '类目编号不能为空',
            'category_id.integer' => '类目编号必须是整数',
            'category_id.min' => '类目编号不能小于1',
            'is_must.in' => '是否必须选必须是：0、1',
            'sort.integer' => '排序依据必须是整数',
            'sort.min' => '排序依据不能小于0',
        ];
    }
}
