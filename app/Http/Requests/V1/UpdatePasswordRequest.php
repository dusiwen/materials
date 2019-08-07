<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'password' => 'required|between:6,25',
            'new_password'=>'required|between:6,25'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '密码不能为空',
            'password.between' => '密码小于6位或大于25位',
            'new_password.required' => '新密码不能为空',
            'new_password.between' => '新密码小于6位或大于25位',
        ];
    }
}
