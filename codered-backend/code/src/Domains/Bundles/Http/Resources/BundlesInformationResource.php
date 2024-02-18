<?php

namespace App\Domains\Bundles\Http\Resources;

use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\Uploads\Http\Resources\FileResource;
use Framework\Traits\SelectColumnTrait;
use INTCore\OneARTFoundation\Http\JsonResource;

class BundlesInformationResource extends JsonResource
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
            'description'            => $this->description,
            'cover_image'            => new FileResource($this->cover_image),
            'image'                  => new FileResource($this->image),
            'course_bundle_features' => $this->features,
            'display_status'         => $this->display_status,
            'is_bestseller'          => $this->is_bestseller,
            'bestseller_description' => $this->bestseller_brief ?? '',
            'is_new_arrival'         => $this->is_new_arrival,
            'newarrival_description' => $this->newarrival_brief ?? '',
            'bundle_type'            => $this->bundle_type,
            'payment_terms'          => [
                'payment_title'   => $this->payment_title,
                'price'           => $this->price,
                'sale_price'      => $this->sale_price,
                'price_period'    => $this->price_period,
                'bundle_url'      => $this->bundle_url,
                'access_pass_url' => $this->access_pass_url,
                'price_features'  => $this->price_features,
            ],

            'learn_features'      => $this->learn_features,
            'is_bundle_spotlight' => $this->is_bundle_spotlight,
            'bundle_spotlight'    => $this->bundle_spotlight,
            'deal_end_date'       => $this->deal_end_date,
            'access_type'         => $this->access_type,
            'courses'             => $this->getCourseDetails(),
            'categories'          => $this->getCategoriesDetails(),

        ];
    }

    private function getCourseDetails()
    {
        if ($this->access_type == AccessType::COURSES) {

            $courses = Course::active()->select(
                'id',
                'name',
                'brief',
                'level',
                'timing',
                'course_type',
                'image_id',
                'course_category_id',
                'intro_video',
                'is_free',
                'slug_url',
                'price'
            )->whereIn('id', $this->access_id)->get();

            return CourseBasicInfoResource::collection($courses);
        }
    }

    private function getCategoriesDetails()
    {
        if ($this->access_type == AccessType::COURSE_CATEGORY) {
            $categories = CourseCategory::select('name', 'image_id', 'activation', 'label_color', 'icon_class_name', 'cat_parent_id')->active()->whereIn('id', $this->access_id)->get();
            return CourseCategoryResource::collection($categories);
        }
        return [];
    }
}
