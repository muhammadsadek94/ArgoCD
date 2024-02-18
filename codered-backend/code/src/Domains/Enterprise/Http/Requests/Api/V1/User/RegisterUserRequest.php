<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\User;

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
            "password" => "required",
//            "phone" => "required|unique:users,phone" ,
            "email" => "required|email" ,
            "image_id" => "exists:uploads,id",
            'sub_account_id' => 'exists:users,id',
//            'tags'           => 'array',
//            'tags.*'         => 'exists:user_tags,id',
            'learn_paths'           => 'array',
            'learn_paths.*'         => 'exists:enterprise_learn_paths,id',
        ];
    }
}
