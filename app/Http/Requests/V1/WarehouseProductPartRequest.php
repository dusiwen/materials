<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseProductPartRequest extends FormRequest
{
    public static $RULES = [
        'name' => 'required|between:2,191',
        'inventory' => 'nullable|integer|min:0',
        'character' => 'nullable|between:2,50',
    ];

    public static $MESSAGES = [
        'name.required' => '名称不能为空',
        'name.between' => '名称必须大于2位或小于191位',
        'inventory.integer' => '库存不能为空',
        'inventory.min' => '库存不能小于0',
        'character.between' => '特性必须大于2位或小于50位',
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
