<?php

namespace App\Domains\ContactUs\Http\Requests\Api;

use INTCore\OneARTFoundation\Http\FormRequest;

class SaveMessageRequest extends FormRequest
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
            "first_name" => "required|max:255",
//            "last_name"  => "required|max:255",
            "email"      => "required|email",
//            "phone"      => "",
//            "subject_id" => "required|exists:contact_us_subjects,id",
            "body"       => "required"
        ];
    }
}
