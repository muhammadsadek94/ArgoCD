<?php

namespace App\Domains\User\Http\Requests\Api\V2\User;

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
//            "last_name" => "required",
            "password" => ["required","min:6","regex:'^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$'"],
//            "phone" => "required|unique:users,phone" ,
            "email" => "required|email" ,
            "image_id" => "exists:uploads,id"
        ];
    }
}
