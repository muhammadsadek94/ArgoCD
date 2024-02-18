<?php

namespace App\Domains\Course\Http\Requests\Api;

use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed lesson_id
 * @property mixed is_checked
 * @property mixed lesson_task_id
 */
class InteractWithLessonTaskRequest extends FormRequest
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
    }
}
