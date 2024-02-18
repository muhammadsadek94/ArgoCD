<?php

namespace App\Domains\Challenge\Http\Requests\Api;

use INTCore\OneARTFoundation\Http\FormRequest;

class FlagSubmissionRequest extends FormRequest
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
            'UserId'        => 'required',
            'CompetitionId' => 'required',
            'FlagId'        => 'required',
            'TotalTime'     => 'required',
            'TimeTaken'     => 'required|date_format:H:i:s',
            'UserScore'     => 'required',
            'DisplayName'   => 'required',
        ];
    }
}
