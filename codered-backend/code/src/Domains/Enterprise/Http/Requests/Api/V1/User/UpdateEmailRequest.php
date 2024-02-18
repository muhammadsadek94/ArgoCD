<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\User;

use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed email
 */
class UpdateEmailRequest extends FormRequest
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
            'email' => 'required|unique:users'
        ];
    }
}
