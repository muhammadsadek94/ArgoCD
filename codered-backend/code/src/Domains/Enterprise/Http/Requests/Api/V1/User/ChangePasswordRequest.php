<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\User;

use INTCore\OneARTFoundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            "password" => "required|confirmed|min:6",
            "old_password" => "required",
        ];
    }
}
