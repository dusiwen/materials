<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceBrandRequest extends FormRequest
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
            'title'=>'required|between:2,191',
            'description'=>'nullable|between:2,191',
            'logo'=>'nullable|between:2,191',
            'status_id'=>'nullable|integer|min:1',
            'official_home_link'=>'nullable|between:2,191',
        ];
    }

    public function messages()
    {
        return [
            'title.required'=>'名称必须存在',
            'title.between'=>'名称不能小于2位或大于191位',
            'logo.between'=>'logo不能小于2位或大于191位',
            'status_id.integer'=>'状态编号必须是整数',
            'status_id.min'=>'状态编号不能小于1',
            'official_home_link.between'=>'官网地址不能小于2位或大于191位',
        ];
    }
}
