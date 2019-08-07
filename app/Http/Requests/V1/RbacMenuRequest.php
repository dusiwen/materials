<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RbacMenuRequest extends FormRequest
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
            'title' => 'required|between:2,50',
            'parent_id' => 'nullable|integer|min:1',
            'sort' => 'nullable|integer|min:0',
            'icon' => 'nullable|between:1,50',
            'uri' => 'nullable|between:1,191',
            'permission_id' => 'nullable|integer|min:1',
            'sub_title' => 'nullable|between:2,191'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '标题不能为空',
            'title.between' => '标题不能小于2位或大于50位',
            'parent_id.integer' => '父级编号必须是整数',
            'parent_id.min' => '父级编号不能小于1',
            'sort.integer' => '排序依据必须是整数',
            'sort.min' => '排序依据不能小于0',
            'icon.between' => '图标名称不能小于1位或大于50位',
            'permission.integer' => '权限编号必须是整数',
            'permission.min' => '权限编号不能小于1',
            'sub_title' => '副标题不能小于2位或大于191位'
        ];
    }
}
