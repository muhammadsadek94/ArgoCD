<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\LearnPath;

use INTCore\OneARTFoundation\Http\FormRequest;

class AssignCourseToLearnPathRequest extends FormRequest
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
            "course"                    => [ "exists:courses,id"],
//            "package_subscription_id"   =>["required", "exists:package_subscription,id"],
        ];
    }
}
