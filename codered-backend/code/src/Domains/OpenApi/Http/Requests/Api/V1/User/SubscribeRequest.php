<?php

namespace App\Domains\OpenApi\Http\Requests\Api\V1\User;

use App\Foundation\Rules\NameRule;
use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed package_id
 * @property mixed subscription_id
 */
class  SubscribeRequest extends FormRequest
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
            "package_id"      => ["required", 'string', 'exists:.package_subscriptions,id'],
            "subscription_id" => ["nullable", "string"],

        ];
    }
}
