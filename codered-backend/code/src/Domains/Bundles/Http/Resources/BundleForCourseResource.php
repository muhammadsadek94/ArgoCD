<?php

namespace App\Domains\Bundles\Http\Resources;

use App\Domains\Configuration\Enum\AccessType;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class BundleForCourseResource extends JsonResource
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
            'id'                     => $this->id,
            'name'                   => $this->name,
            'description'            => $this->description ?? '',
            'cover_image'            => new FileResource($this->cover_image),
            'image'                  => new FileResource($this->image),
            'bundle_type'            => $this->bundle_type,
            'learn_features'      => $this->learn_features,
            'is_bundle_spotlight' => $this->is_bundle_spotlight,
            'bundle_spotlight'    => $this->bundle_spotlight,
            'deal_end_date'       => $this->deal_end_date,
            'access_type'         => $this->access_type,
        ];

    }

}
