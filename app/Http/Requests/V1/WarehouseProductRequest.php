<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseProductRequest extends FormRequest
{
    public static $RULES = [
        'name' => 'required|between:2,191',
        'organization_code' => 'nullable|between:1,50'
    ];

    public static $MESSAGES = [
        'name.required' => '名称不能为空', 'name.between' => '名称不能小于2位或大于191位',
        'organization_code.between' => '机构代码不能小于1位或大于50位',
    ];

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
        return self::$RULES;
    }

    public function messages()
    {
        return self::$MESSAGES;
    }
}
