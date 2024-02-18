<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\SubAccount;

use App\Foundation\Rules\NameRule;
use INTCore\OneARTFoundation\Http\FormRequest;

class AddLearnPathToSubAccountRequest extends FormRequest
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
            'learn_paths'           => 'required',
            'learn_paths.*'         => 'exists:package_subscriptions,id',
        ];
    }
}
