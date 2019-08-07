<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class EntireInstanceRequest extends FormRequest
{
    public static $RULES = [];

    public static $MESSAGES = [];


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
