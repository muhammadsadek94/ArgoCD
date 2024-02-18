<?php

namespace App\Domains\Challenge\Http\Requests\Api;

use INTCore\OneARTFoundation\Http\FormRequest;

class CompetitionCompletedRequest extends FormRequest
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
            "UserId"            => 'required',
            "EventId"           => 'required',
            "IsLabLaunched"     => 'required',
            "IsLabCompleted"    => 'required',
            "StartDatetime"     => 'required',
            "EndDateTime"       => 'required',
            "ExamScore"         => 'required',
            "CompetitionId"     => 'required',
            "LabType"           => 'required',
            "TotalScore"        => 'required',
        ];
    }
}
