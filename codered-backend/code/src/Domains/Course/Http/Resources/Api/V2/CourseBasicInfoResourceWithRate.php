<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseBasicInfoResourceWithRate extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'level'                 => $this->level,
            'timing'                => $this->timing,
            'course_type'           => $this->course_type,
            'image'                 => new FileResource($this->whenLoaded('image')),
            'category'              => new CourseCategoryResource($this->whenLoaded('category') ?? $this->whenLoaded('sub')),
            'price'                 => $this->price,
            'enrolled'              => $request->user('api') ? $request->user('api')->course_enrollments()->where('course_id', $this->id)->count() > 0 : false,
            'completion_percentage' => $this->when($request->user('api'), function () use ($request) {
                return $this->completedPercentage($request->user('api'));
            }),
            'is_free'               => $this->is_free,
            'url'                   => config('user.user_website') . "/course/{$this->id}",
            'slug_url'              => $this->slug_url,
            'rate'                => $this->getAverageRate() ?? 0,
            'reviews_count'       => $this->reviews->count() ?? 0,
            'assessments_count'     => $this->getCourseAssesmentCount(),
            'discount_price'        => $this->discount_price,

        ];
    }

    private function getAverageRate()
    {
        $reviews = $this->reviews()->where('activation', true);
        if(!$reviews->count()) return null;
            return round($reviews->sum('rate') / $reviews->count() , 0);
    }

    private function getCourseAssesmentCount()
    {
        $assessments = $this->assessments();
        if(!$assessments->get()) return null;
            return $assessments->count();
    }
}
