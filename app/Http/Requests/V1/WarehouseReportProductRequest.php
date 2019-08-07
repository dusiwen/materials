<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseReportProductRequest extends FormRequest
{
    public static $RULES = [
        'draw_person_name' => 'nullable|between:1,50',
        'draw_person_phone' => 'nullable|size:11',
        'out_person_id' => 'nullable|integer|min:1',
        'maintain_id' => 'nullable|integer|min:1',
        'in_person_id' => 'nullable|integer|min:1',
        'send_person_name' => 'nullable|between:1,50',
        'send_person_phone' => 'nullable|size:11',
        'in_reason' => 'required|in:BUY,FIX_BY_SEND,FIX_AT_TIME,FIX_TO_OUT_FINISH',
        'out_reason' => 'required|in:FIX_BY_SEND_FINISH,FIX_TO_OUT,SCRAP',
        'warehouse_product_instance_open_code' => 'required|between:1,50',
    ];

    public static $MESSAGES = [
        'draw_person_name.between' => '接收人姓名',
        'draw_person_phone.size' => '接收人手机号',
        'out_person_id.integer' => '出库人编号必须是数字',
        'out_person_id.min' => '出库人编号必须大于1',
        'maintain_id.integer' => '台账编号必须是数字',
        'maintain_id.min' => '台账编号不能小于1',
        'is_person_id.integer' => '入库人编号必须是数字',
        'is_person_id.min' => '入库人编号不能小于1',
        'send_person_name.between' => '送检人姓名',
        'send_person_phone.size' => '送检人手机号',
        'in_reason.required' => '入库原因不能为空',
        'in_reason.in' => '入库原因类型错误',
        'out_reason.required' => '出库原因不能为空',
        'out_reason.in' => '出库原因类型错误',
        'warehouse_product_instance_id.required' => '设别编号不能为空',
        'warehouse_product_instance_id.between' => '设别编号不能大于50位',
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
