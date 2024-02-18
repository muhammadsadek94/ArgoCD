<?php

namespace App\Domains\User\Http\Requests\Api\V2\Instructor;

use App\Foundation\Rules\NameRule;
use INTCore\OneARTFoundation\Http\FormRequest;

class   RegisterInstructorRequest extends FormRequest
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
            "first_name" => ["required"],
//            "last_name" => "required",
            "phone" => "required|unique:users,phone" ,
            "email" => "required|email|unique:users,email" ,

            "image_id" => "exists:uploads,id",
            "cv_id" => "required|exists:uploads,id",
            "video_sample_id" => "exists:uploads,id",

        ];
    }
}
