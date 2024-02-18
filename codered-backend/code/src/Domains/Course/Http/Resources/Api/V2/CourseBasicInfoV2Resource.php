<?php

namespace App\Domains\Course\Http\Resources\Api\V2;

use App\Domains\Course\Models\CourseReview;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Enum\ExperienceLevels;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseBasicInfoV2Resource extends JsonResource {
    public function toArray($request)
    {
        $user = $request->user('api');

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'level'                 => $this->level,
            'timing'                => (int) ($this->timing / 60),
            'course_type'           => $this->course_type,
            'image'                 => new FileResource($this->whenLoaded('image')),
            'url'                   => config('user.user_website') . "/course/{$this->id}",
            'slug_url'              => $this->slug_url,
            'is_free'               => $this->is_free,
            'price'                 => $this->price,
            'reviews_count'         => $this->reviews->count(),
            'rate'                  => $this->countRate($this->reviews),
            'category'              => new CourseCategoryResource($this->sub ?? $this->category),
            'discount_price'        => $this->discount_price,
            'has_access'            => $user ? has_access_course_eager($this->resource, $user) : false

        ];
    }

    public function countRate($reviews) {
        $count = $reviews->count();
        $totalRates = $reviews->sum('rate');
        if(!$count) return 0;
        return ceil($totalRates / $count);
    }
}
