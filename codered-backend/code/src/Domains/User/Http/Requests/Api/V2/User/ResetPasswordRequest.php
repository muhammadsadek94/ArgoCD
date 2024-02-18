<?php

namespace App\Domains\User\Http\Requests\Api\V2\User;

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
            "password" => ["required","min:6","regex:'^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$'"],
        ];
    }
}
