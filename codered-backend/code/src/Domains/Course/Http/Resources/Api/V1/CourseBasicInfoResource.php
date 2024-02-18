<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseBasicInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $user = $request->user('api');

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'level'                 => $this->level,
            'timing'                => $this->timing ? round($this->timing / 60, 1) : 0,
            'course_type'           => $this->course_type,
            'image'                 => new FileResource($this->whenLoaded('image')),
            'category'              => $this->course_sub_category_id ? new CourseCategoryResource($this->whenLoaded('sub')) : new CourseCategoryResource($this->whenLoaded('category')),
            'enrolled'              => $request->user('api') ? $request->user('api')->course_enrollments()->where('course_id', $this->id)->count() > 0 : false,
            'completion_percentage' => $this->when($request->user('api'), function () use ($request) {
                return $this->completedPercentage($request->user('api'), false);
            }),
            'intro_video'         => $this->intro_video,
            'is_free'               => $this->is_free,
            'url'                   => config('user.user_website') . "/course/{$this->id}",
            'slug_url'              => $this->slug_url,
            'tags'          => $this->tags()->active()->get()->map(function ($row) {
                return $row->name;
            }),
            'price' => $this->price,

        ];
    }
}
