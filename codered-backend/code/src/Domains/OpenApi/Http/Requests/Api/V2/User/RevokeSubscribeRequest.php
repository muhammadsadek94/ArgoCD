<?php

namespace App\Domains\OpenApi\Http\Requests\Api\V2\User;

use App\Foundation\Rules\NameRule;
use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed package_id
 * @property mixed subscription_id
 */
class RevokeSubscribeRequest extends FormRequest
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
            "subscription_id" => ["required", "string", 'exists:user_subscriptions,subscription_id'],

        ];
    }
}
