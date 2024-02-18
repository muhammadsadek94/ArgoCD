<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\SubAccount;

use INTCore\OneARTFoundation\Http\FormRequest;

class UpdateSubAccountProfileRequest extends FormRequest
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
        //ignore this id while identify unique user
        return [
            'first_name' => 'required',
            'email' => 'required|unique:users,email,' . request()->subAccount,
        ];
    }

}
