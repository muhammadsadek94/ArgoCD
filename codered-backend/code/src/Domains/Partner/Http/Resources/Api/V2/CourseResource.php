<?php

namespace App\Domains\Partner\Http\Resources\Api\V2;

use App\Domains\Course\Http\Resources\Api\V2\CourseCategoryResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'level'                 => $this->level,
            'timing'                => $this->timing,
            'course_type'           => $this->course_type,
            'image'                 => new FileResource($this->whenLoaded('image')),
            'category'              => new CourseCategoryResource($this->whenLoaded('category')),
            'enrolled'              => $request->user('api') ? $request->user('api')->course_enrollments()->where('course_id', $this->id)->count() > 0 : false,
            'completion_percentage' => $this->when($request->user('api'), function () use ($request) {
                return $this->completedPercentage($request->user('api'));
            }),
            'final_exam_timer' => $this->when($request->user('api'), function () use ($request) {
                    return $this->finalExamTimer($request->user('api'));
            }),
            'is_free'               => $this->is_free,
            'url'                   => config('user.user_website') . "/course/{$this->id}",
            'slug_url'              => $this->slug_url,

            'has_access'            => $request->user('api') ? has_access_course($this->resource) : false
        ];
    }
}
