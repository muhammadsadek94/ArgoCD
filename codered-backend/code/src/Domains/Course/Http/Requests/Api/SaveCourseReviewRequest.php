<?php

namespace App\Domains\Course\Http\Requests\Api;

use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed course_id
 * @property mixed rate
 * @property mixed name
 * @property mixed user_goals
 * @property mixed recommendation
 */
class SaveCourseReviewRequest extends FormRequest
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
            'rate' => 'required|integer|between:1,5',
        ];
    }
}
