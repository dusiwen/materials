<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
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
            'name'=>'required|between:2,191|unique:organizations',
            'parent_id'=>'nullable|integer|min:0',
            'level'=>'nullable|integer|min:0',
            'is_main'=>'nullable|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'名称必须存在',
            'name.between'=>'名称不能小于2位或大于191位',
            'name.unique'=>'名称被占用',
            'parent_id.integer'=>'父级编号必须是数字',
            'parent_id.min'=>'父级编号不能小于0',
            'level.integer'=>'等级必须是数字',
            'level.min'=>'等级不能小于0',
            'is_main.in'=>'是否是主体机构必须是：0、1',
        ];
    }
}
