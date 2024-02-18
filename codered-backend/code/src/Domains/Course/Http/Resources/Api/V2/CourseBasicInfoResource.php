<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Models\Course;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

//
class CourseBasicInfoResource extends JsonResource
{
    public function toArray($request)
    {

        $user = $request->user('api');
        $user_course_enrollments = null;
        $user_completion_percentage = null;
        if ($user) {
            $user_course_enrollments = $user?->course_enrollments;
            //            $user_completion_percentage = $this->whenLoaded('completedPercentageLoad', function () use ($user) {
            //                return $this->completedPercentageLoad->where('user_id', $user->id)->first();
            //            });
        }

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'level'                 => $this->level,
            'timing'                => $this->timing,
            'category'              => new CourseCategoryResource($this->sub ?? $this->category),
            'course_type'           => $this->course_type,
            'image'                 => new FileResource($this->whenLoaded('image')),
            'url'                   => config('user.user_website') . "/course/{$this->id}",
            'slug_url'              => $this->slug_url,
            'is_free'               => $this->is_free,
            'price'                 => $this->price,
            'discount_price'        => $this->discount_price,
            'reviews_count'         => $this->agg_count_reviews,
            'rate'                  => round($this->agg_avg_reviews, 1),
            'enrolled'              => $user ? $user_course_enrollments->where('id', $this->id)->count() > 0 : false,
            'enrollment'            => $this->agg_count_course_enrollment,
            //            $this->mergeWhen($user && $this->whenLoaded('completedPercentageLoad'),[
            //                'completion_percentage' => ($user && $user_completion_percentage) ? round((int)($user_completion_percentage?->completed_percentage), 1) : 0,
            //                'is_finished'           => ($user && $user_completion_percentage) ? !!$user_completion_percentage?->is_finished : false,
            //            ]),
            'completion_percentage' => $this->whenLoaded('completedPercentageLoad', function () use ($user) {
                return ($user) ? round((int) ($this->completedPercentageLoad?->first()?->completed_percentage), 1) : 0;
            }),
            'is_finished'           => $this->whenLoaded('completedPercentageLoad', function () use ($user) {
                return ($user) ? !!$this->completedPercentageLoad?->first()?->is_finished : false;
            }),
            'has_access'            => $user ? has_access_course_eager($this->resource, $user) : false,
            'menu_cover'            => new FileResource($this->menu_cover),

        ];
    }
}
