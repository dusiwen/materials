<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PivotNoticeGroupDeviceRequest extends FormRequest
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
            'notice_group_id' => 'required|integer|min:1',
            'device_open_code' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'notice_group_id.required' => '通知组编号不能为空',
            'notice_group_id.integer' => '通知组编号必须是整数',
            'notice_group_id.min' => '通知组编号不能小于1',
            'device_open_code' => '设备开放标识不能为空'
        ];
    }
}
