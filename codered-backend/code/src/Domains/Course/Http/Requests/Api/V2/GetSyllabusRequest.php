<?php

namespace App\Domains\Course\Http\Requests\Api\V2;

use App\Foundation\Rules\NameRule;
use App\Foundation\Rules\PhoneNumberRule;
use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $email
 * @property mixed $phone
 * @property mixed $course_name
 */
class GetSyllabusRequest extends FormRequest
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
            'first_name'  => ['required', 'string', new NameRule()],
            'last_name'   => ['required', 'string', new NameRule()],
            'email'       => ['required', 'email'],
            'phone'       => ['required', new PhoneNumberRule()],
            'course_name' => ['required', 'string'],
        ];
    }
}
