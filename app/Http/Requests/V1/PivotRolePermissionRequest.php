<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PivotRolePermissionRequest extends FormRequest
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
            'rbac_role_id'=>'required|integer|min:1',
            'rbac_permission_id'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'rbac_role_id.required'=>'角色编号不能为空',
            'rbac_role_id.integer'=>'角色编号必须是整数',
            'rbac_role_id.min'=>'角色编号不能小于1',
            'rbac-permission_id.required'=>'权限编号不能为空'
        ];
    }
}
