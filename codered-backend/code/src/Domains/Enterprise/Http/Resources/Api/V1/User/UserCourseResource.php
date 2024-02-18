<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Http\JsonResource;

class UserCourseResource extends JsonResource
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
            'enrolled'              => $request->user ?User::where('id',$request->user)->first()->course_enrollments()->where('course_id', $this->id)->count() > 0 : false,
            'completion_percentage' => $this->when($request->user, function () use ($request) {
                return $this->completedPercentage(User::where('id',$request->user)->first());
            }),
            'is_free'               => $this->is_free,
            'url'                   => config('user.user_website') . "/course/{$this->id}",
            'slug_url'              => $this->slug_url,
        ];
    }
}
