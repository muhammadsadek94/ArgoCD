<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\User;

use INTCore\OneARTFoundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            "code" => "required",
            "phone" => "required_without:email" ,
            "email" => "required_without:phone",
            "password" => "required|min:6",
        ];
    }
}
