<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public static $RULES = [
        'name' => 'required|between:2,50',
        'unique_code' => 'nullable|between:2,50|unique:categories'
    ];

    public static $MESSAGES = [
        'name.required' => '名称不能为空',
        'name.between' => '名称不能小于2位或大于50位',
        'unique_code.between' => '唯一表示不能小于2位或大于50位',
        'unique_code.unique'=>'唯一标识已被占用'
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
