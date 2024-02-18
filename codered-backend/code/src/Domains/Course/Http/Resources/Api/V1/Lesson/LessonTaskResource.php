<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Lesson;

use INTCore\OneARTFoundation\Http\JsonResource;

class LessonTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_checked' => auth()->user()->lesson_tasks()->where('lesson_task_id', $this->id)->count() > 0
        ];
    }
}
