<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceGroupDeviceRequest extends FormRequest
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
            'device_group_id'=>'required|integer|min:1',
            'device_open_code'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'device_group_id.required'=>'设备分组编号不能为空',
            'device_group_id.integer'=>'设备分组编号必须是整数',
            'device_group_id.min'=>'设备分组编号不能小于1',
            'device.required'=>'设备开放标识不能为空',
        ];
    }
}
