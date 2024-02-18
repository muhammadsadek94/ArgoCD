<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Lesson;

use INTCore\OneARTFoundation\Http\JsonResource;

class LessonFaqResource extends JsonResource
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
            'answer'   => $this->answer,
            'question'   => $this->question,
        ];
    }

}
