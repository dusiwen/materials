<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class MeasurementRequest extends FormRequest
{
    public static $RULES = [
        'entire_model_unique_code' => 'required|between:1,100',
        'part_model_unique_code' => 'nullable|between:1,100',
        'key' => 'nullable|between:1,50',
        'allow_min' => 'nullable',
        'allow_max' => 'nullable',
        'allow_explain'=>'nullable|between:1,100',
        'unit' => 'nullable|between:1,50',
        'operation'=>'nullable|between:1,50',
    ];

    public static $MESSAGES = [
        'entire_model_unique_code.required' => '设备型号代码不能为空',
        'entire_model_unique_code.between' => '设备型号代码不能大于100位',
        'part_model_unique_code.between' => '部件型号代码不能大于100位',
        'key.between'  => '测试项不能大于50位',
        'allow_min.between' => '允许最小值必须是数字',
        'allow_max.between' => '允许最大值必须是数字',
        'allow_explain'=>'标准参考不能大于100位',
        'unit.between' => '单位不能大于50位',
        'operation.between'=>'操作分项不能大于50位',
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
