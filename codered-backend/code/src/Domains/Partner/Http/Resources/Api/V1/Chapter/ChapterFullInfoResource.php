<?php

namespace App\Domains\Partner\Http\Resources\Api\V1\Chapter;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Partner\Http\Resources\Api\V1\Lesson\LessonFullInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class ChapterFullInfoResource extends JsonResource
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
            'id'     => $this->id,
            'name'   => $this->name,
            'description' => $this->description,
            'lessons' => LessonFullInfoResource::collection($this->getLessons())
        ];
    }

    private function getLessons()
    {
        return $this->lessons()
            ->where(function($query) {
                $query->where(function($query) {
                    return $query->whereIn('type', [
                        LessonType::DOCUMENT,
                        LessonType::LAB,
                        LessonType::QUIZ,
                    ]);
                })->orWhere(function($query) {
                    return $query->whereIn('type', [
                        LessonType::VIDEO
                    ])->whereNotNull('video');
                });
            })
            ->active()
            ->get();
    }
}
