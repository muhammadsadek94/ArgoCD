<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1;

use INTCore\OneARTFoundation\Http\FormRequest;

class DeactivateUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'           => 'required',
            'user_id.*'         => 'exists:users,id',
        ];
    }
}
