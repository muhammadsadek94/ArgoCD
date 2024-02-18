<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\User;

use App\Foundation\Rules\NameRule;
use INTCore\OneARTFoundation\Http\FormRequest;

class CreateUserWithFileRequest extends FormRequest
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
//            'tags.*'         => 'exists:user_tags,id',
            'learn_paths'           => 'array',
            'learn_paths.*'         => 'exists:enterprise_learn_paths,id'
        ];
    }
}
