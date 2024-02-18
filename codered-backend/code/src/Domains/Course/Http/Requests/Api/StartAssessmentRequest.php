<?php

namespace App\Domains\Course\Http\Requests\Api;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\Course;
use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed course_id
 */
class StartAssessmentRequest extends FormRequest
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
        $checkIfCourseIsMicroDegree = Course::where(['id' => $this->course_id])->orWhere(['slug_url' => $this->course_id])->firstOrFail()->course_type == CourseType::MICRODEGREE;

        // $checkIfCourseIsMicroDegree = Course::find($this->course_id)->course_type == CourseType::MICRODEGREE;
        if($checkIfCourseIsMicroDegree) {
            return [
                'username' => 'required',
                'password' => 'required'
            ];
        }

        return [];
    }
}
