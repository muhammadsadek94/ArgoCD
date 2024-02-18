<?php

namespace App\Domains\User\Http\Requests\Api\V1\User;

use INTCore\OneARTFoundation\Http\FormRequest;

class VerifyUpdateEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'temp_code'=>'required'
        ];
    }
}
