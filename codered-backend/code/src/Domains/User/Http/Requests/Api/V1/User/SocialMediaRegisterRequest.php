<?php

namespace App\Domains\User\Http\Requests\Api\V1\User;

use INTCore\OneARTFoundation\Http\FormRequest;

class SocialMediaRegisterRequest extends FormRequest
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
            "first_name"  => "required",
//            "last_name"   => "required",
//            "phone"       => "required_without:email|unique:users,phone",
            "email"       => "required_without:phone|email|unique:users,email",
            "social_type" => "required",
            "social_id"   => "required|unique:users,social_id",
            "image_id"    => "exists:uploads,id",
        ];
    }
}
