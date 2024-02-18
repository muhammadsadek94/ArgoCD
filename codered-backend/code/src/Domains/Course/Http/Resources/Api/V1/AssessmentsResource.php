<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use INTCore\OneARTFoundation\Http\JsonResource;

class AssessmentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->assessment_id,
            'question'              => $this->assessment->question,
            'answers'               => $this->getAnswers(),
            'selected_answers'      => $this->assessment_answer ?  $this->assessment_answer->id : null,
        ];
    }


    public function getAnswers(): array
    {
        $answers = [];

        foreach ($this->assessment->answers as $answer) {
            $data = [
                'id' => $answer->id,
                'answer' => $answer->answer
            ];
            $answers[] = $data;
        }

        return $answers;
    }
}
