<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Chapter;

use App\Domains\Course\Enum\LessonType;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;

//use Faker\Generator as Faker;
use Str;
use Faker\Factory as Faker;
use App\Domains\User\Http\Resources\Api\V1\User\InstructorBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonBasicInfoResource;


class ChapterBasicInfoResource extends JsonResource
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
            'id'     => $this->id,
            'name'   => $this->name,
            'lessons' => LessonBasicInfoResource::collection($this->getLessons()),
            'description' => $this->description
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
                        LessonType::CYPER_Q,
                        LessonType::VOUCHER,
                        LessonType::VITAL_SOURCE
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
