<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class NoticeGroupRequest extends FormRequest
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
            'name'=>'required|between:2,50',
            'organization_id'=>'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'名称不能为空',
            'name.between'=>'名称不能小于2位或大于50位',
            'organization_id.required'=>'机构编号不能为空',
            'organization_id.integer'=>'机构编号必须是数字',
            'organization_id.min'=>'机构编号不能小于1',
        ];
    }
}
