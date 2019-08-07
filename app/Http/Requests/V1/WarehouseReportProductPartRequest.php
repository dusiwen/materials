<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseReportProductPartRequest extends FormRequest
{
    public static $RULES = [
        'number' => 'required|integer|min:1',
        'send_person_name' => 'nullable|between:2,50',
        'send_person_phone' => 'nullable|size:11',
        'in_at' => 'required',
    ];

    public static $MESSAGE = [
        'number.required' => '数量不能为空',
        'number.integer' => '数量必须是数字',
        'number.min' => '数量不能小于1',
        'send_person_name.between' => '送货人姓名不能小于2为或大于50位',
        'send_person_phone.size' => '送货人电话必须是11位',
        'in_at.required' => '入库日期不能为空'
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
        return self::$MESSAGE;
    }
}
