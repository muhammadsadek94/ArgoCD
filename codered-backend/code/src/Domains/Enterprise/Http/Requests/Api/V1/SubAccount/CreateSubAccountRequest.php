<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\SubAccount;

use App\Foundation\Rules\NameRule;
use INTCore\OneARTFoundation\Http\FormRequest;

class CreateSubAccountRequest extends FormRequest
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
            "first_name" => ["required"],
            "email" => "required|email|unique:users,email" ,
            'sub_account_id' => 'exists:users,id',
            'learn_paths'           => 'array',
            'learn_paths.*'         => 'exists:package_subscriptions,id',
        ];
    }
}
