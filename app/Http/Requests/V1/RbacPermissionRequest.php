<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RbacPermissionRequest extends FormRequest
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
            'rbac_permission_group_id' => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名称不能为空',
            'name.between' => '名称不能小于2位或大于50位',
            'rbac_permission_group_id.integer' => '权限分组必须是整数',
            'rbac_permission_group_id.min' => '权限分组不能小于1',
        ];
    }
}
