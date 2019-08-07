<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeviceVideoRequest extends FormRequest
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
            'sort' => 'nullable|integer|min:0',
            'organization_id' => 'required|integer|min:1',
            'spu_id' => 'required|integer|min:1',
            'sku_id' => 'required|integer|min:1',
            'device_group_id' => 'nullable|integer|min:1',
            'is_group' => 'nullable|boolean',
            'open_code' => 'required|unique:devices|between:2,191',
            'device_category_id' => 'nullable|integer|min:1',
            'device_status_id' => 'nullable|integer|min:1',
            'battery_voltage' => 'nullable|double|min:0',
            'electric_quantity' => 'nullable|double|min:0',
            'temperature' => 'nullable|double|min:0',
            'total_working_time' => 'nullable|double|min:0',
            'working_time' => 'nullable|double|min:0',
            'signal_4g' => 'nullable|double|min:0',
            'signal_2g' => 'nullable|double|min:0',
            'free_ram' => 'nullable|double|min:0',
            'free_rom' => 'nullable|double|min:0',
        ];
    }

    public function messages()
    {
        return [
            'sort.integer' => '排序依据必须是整数',
            'sort.min' => '排序依据不能小于0',
            'organization_id.required' => '机构编号不能为空',
            'organization_id.integer' => '机构编号必须是数字',
            'organization_id.min' => '机构编号不能小于1',
            'spu_id.required' => 'SPU编号不能为空',
            'spu_id.integer' => 'SPU编号必须是数字',
            'spu_id.min' => 'SPU编号不能小于1',
            'sku_id.required' => 'SKU编号不能为空',
            'sku_id.integer' => 'SKU编号必须是数字',
            'sku_id.min' => 'SKU编号不能小于1',
            'device_group_id.integer' => '设备分组编号必须是数字',
            'device_group_id.min' => '设备分组编号不能小于1',
            'is_group.boolean' => '集群设备标识错误',
            'open_code.required' => '开放标识不能为空',
            'open_code.unique' => '开放标识已被占用',
            'open_code.between' => '开放标识不能小于2位或大于191位',
            'device_category_id.integer' => '设备类目编号必须是整数',
            'device_category_id.min' => '设备类目编号不能小于1',
            'device_status_id.integer' => '设备状态编号必须是整数',
            'device_status_id.min' => '设备状态编号不能小于1',
            'battery_voltage.double' => '电池电压必须是数字',
            'battery_voltage.min' => '电池电压不能小于0',
            'electric_quantity.double' => '电量必须是数字',
            'electric_quantity.min' => '电量不能小于0',
            'temperature.double' => '设备温度必须是数字',
            'temperature.min' => '设备温度不能小于0',
            'total_working_time.double' => '工作总时长必须是数字',
            'total_working_time.min' => '工作总时长不能小于0',
            'working_time.double' => '工作总市场不能小于0',
            'working_time.min' => '工作总市场不能小于0',
            'signal_4g.double' => '4G信号必须是数字',
            'signal_4g.min' => '4G信号不能小于0',
            'signal_2g.double' => '2G信号必须是数字',
            'signal_2g.min' => '2G信号不能小于0',
            'free_ram.double' => 'RAM剩余空间必须是数字',
            'free_ram.min' => 'RAM剩余空间不能小于0',
            'free_rom.double' => 'ROM剩余空间必须是数字',
            'free_rom.min' => 'ROM剩余空间不能小于0',
        ];
    }
}
