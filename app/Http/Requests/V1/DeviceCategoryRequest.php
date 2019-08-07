<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceCategoryRequest extends FormRequest
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
            'name'=>'required|between:2,25',
            'parent_id'=>'nullable|integer|min:1',
            'sort'=>'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'名称不能为空',
            'name.between'=>'名称不能小于2位或大于25位',
            'parent_id.integer'=>'父级编号必须是整数',
            'parent_id.min'=>'父级编号不能小于1',
            'sort.integer'=>'排序依据必须是整数',
            'sort.min'=>'排序依据不能小于1'
        ];
    }
}
