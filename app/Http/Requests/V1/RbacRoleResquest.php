<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RbacRoleResquest extends FormRequest
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
            'name'=>'required|unique:rbac_roles|between:2,50'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'名称不能为空',
            'name.unique'=>'名称被占用',
            'name.between'=>'名称不能小于2位或大于50位',
        ];
    }
}
