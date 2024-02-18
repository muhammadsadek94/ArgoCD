<?php

namespace App\Domains\User\Http\Requests\Api\V1\User;

use App\Domains\User\Enum\ExperienceLevels;
use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed level
 * @property mixed goals
 * @property mixed course_tags
 * @property mixed course_categories
 */
class OnBoardingQuizRequest extends FormRequest
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
            'course_categories'   => "required|array",
            'course_categories.*' => "exists:course_categories,id",
            'course_tags'         => "required|array",
            'course_tags.*'       => "exists:course_tags,id",
            'goals'               => "required|array",
            'goals.*'             => "exists:goals,id",

            'level' => [
                'required',
                'in:' . ExperienceLevels::BEGINNER . ',' . ExperienceLevels::INTERMEDIATE . ',' . ExperienceLevels::ADVANCED
            ]
        ];
    }
}
