<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CompletedCourseResource extends JsonResource
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
            'id'                    => $this->course->id,
            'name'                  => $this->course->name,
            'brief'                 => $this->course->brief,
            'level'                 => $this->course->level,
            'timing'                => $this->course->timing,
            'course_type'           => $this->course->course_type,
            // 'image'                 => new FileResource($this->course->whenLoaded('image')),
            // 'category'              => new CourseCategoryResource($this->course->whenLoaded('category')),
            'enrolled'              => $request->user('api') ? $request->user('api')->course_enrollments()->where('course_id', $this->course->id)->count() > 0 : false,
            'completion_percentage' => $this->course->when($request->user('api'), function () use ($request) {
                return $this->course->completedPercentage($request->user('api'));
            }),
            'is_free'               => $this->course->is_free,
            'url'                   => config('user.user_website') . "/course/{$this->course->id}",
            'slug_url'              => $this->course->slug_url,
            'degree'                => $this->degree
        ];
    }
}
