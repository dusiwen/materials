<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReportSensorRequest extends FormRequest
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
            'device_open_code' => 'required|between:2,191',
        ];
    }

    public function messages()
    {
        return [
            'device_open_code.required'=>'设备唯一标志不能为空',
            'device_open_code.between'=>'设备唯一标志不能小于2位或大于191位',
        ];
    }
}
