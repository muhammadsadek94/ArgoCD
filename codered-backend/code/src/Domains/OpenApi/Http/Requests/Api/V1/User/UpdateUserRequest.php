<?php

namespace App\Domains\OpenApi\Http\Requests\Api\V1\User;

use App\Foundation\Rules\NameRule;
use INTCore\OneARTFoundation\Http\FormRequest;

class  UpdateUserRequest extends FormRequest
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
        $user = $this->user('api');
        return [
            "first_name"       => ["required", new NameRule()],
            "last_name"       => ["required", new NameRule()],
            "email"            => "required|email|unique:users,email,{$user->id}",
            "phone"            => "required|unique:users,phone,{$user->id}",
        ];
    }
}
