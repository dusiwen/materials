<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
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
            'account' => 'required|between:2,191',
            'password' => 'required|between:6,25',
            'code'=>'required|size:4',
            'type'=>'required|in:email,sms'
        ];
    }

    public function messages()
    {
        return [
            'account.required' => '账号不能为空',
            'account.between' => '账号不能小于2位或大于191位',
            'password.required' => '密码不能为空',
            'password.between' => '密码小于6位或大于25位',
            'code.required'=>'验证码不能为空',
            'code.size'=>'验证码必须是4位',
            'type.required'=>'验证码类型不能为空',
            'type.in'=>'验证码类型只能是：email、sms',
        ];
    }
}
