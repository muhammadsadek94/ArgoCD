<?php

namespace App\Domains\Partner\Http\Resources\Api\V1\Lesson;

use INTCore\OneARTFoundation\Http\JsonResource;

class LessonFullInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $course = $this->course;
        $course_category = $course->load('category');
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'timing'        => $this->time,
            'type'          => $this->type,
            'course_name'   => $course->name,
            'course_id'     => $this->course_id,
            'category_name' => $course_category->name,
            'chapter_id'    => $this->chapter_id,
            'overview'   => $this->overview,
            'video'      => $this->video ? $this->getVideoData($this->video) : null,
            'quiz'       => LessonQuizResource::collection($this->mcq),
            'resources'  => LessonAttachmentsResource::collection($this->resources),
            'faq'        => LessonFaqResource::collection($this->faq),
            'is_free'   => $this->is_free,

        ];
    }

    private function getVideoData($video)
    {
        return [
            'account_id' => @$video['account_id'],
            'player_id'  => @$video['player_id'],
            'video_id'   => @$video['video_id'],
        ];
    }

}
