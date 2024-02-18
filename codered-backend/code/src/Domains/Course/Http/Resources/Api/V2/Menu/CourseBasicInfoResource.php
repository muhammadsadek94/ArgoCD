<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Menu;

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
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'course_type'    => $this->course_type,
            'url'            => config('user.user_website') . "/course/{$this->id}",
            'slug_url'       => $this->slug_url,
            'price'          => $this->price,
            'discount_price' => $this->discount_price,
        ];
    }
}
