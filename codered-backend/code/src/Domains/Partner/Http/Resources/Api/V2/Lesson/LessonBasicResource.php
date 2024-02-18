<?php

namespace App\Domains\Partner\Http\Resources\Api\V2\Lesson;

use App\Domains\Course\Models\Lesson;
use INTCore\OneARTFoundation\Http\JsonResource;

class LessonBasicResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'type'          => $this->type,
            'is_available' => lesson_access_permission($this->resource),
            'is_watched' => $request->user('api') ? $request->user('api')->watched_lessons->where('lesson_id', $this->id)->count() > 0 : false,
        ];
    }

}
