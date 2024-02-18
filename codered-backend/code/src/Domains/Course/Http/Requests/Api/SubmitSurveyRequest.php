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
class SubmitSurveyRequest extends FormRequest
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
            'lesson_id' => 'required|exists:lessons,id',
            'chapter_id' => 'required|exists:chapters,id',
            'course_id' => 'required|exists:courses,id',
            'survey' => 'required',
        ];
    }
}
