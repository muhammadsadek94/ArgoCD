<?php

namespace App\Domains\Partner\Http\Resources\Api\V1\Lesson;

use INTCore\OneARTFoundation\Http\JsonResource;

class LessonQuizResource extends JsonResource
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
            'question'    => $this->question,
            'description' => $this->description,
            'answers'     => $this->answers_array,
        ];
    }

}
