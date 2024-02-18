<?php

namespace App\Domains\OpenApi\Http\Requests\Api\V2\User;

use App\Foundation\Rules\NameRule;
use INTCore\OneARTFoundation\Http\FormRequest;

class  RegisterUserRequest extends FormRequest
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
            "first_name" => ["required", new NameRule()],
            "password" => "required|min:6",
            "email" => "required|email|unique:users,email" ,
            'oauth2_client_id' => 'required|exists:oauth_clients,id'
        ];
    }
}
