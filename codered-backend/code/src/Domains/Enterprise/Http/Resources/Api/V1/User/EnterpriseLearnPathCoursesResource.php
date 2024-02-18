<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Course\Http\Resources\Api\V1\Chapter\ChapterBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V1\User\InstructorBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;

class EnterpriseLearnPathCoursesResource extends JsonResource
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
            'id'                  => $this->id,
            'name'                => $this->name,
            'activation'          => $this->activation,
            'image'               => new \Illuminate\Http\Resources\Json\JsonResource($this->image),
            'cover'               => new FileResource($this->cover),
            'category'            => new CourseCategoryResource($this->category),
            'brief'               => $this->brief,
            'description'         => $this->description,
            'level'               => $this->level,
            'timing'              => $this->timing,
            'learn'               => $this->learn,
            'weight'               => $this->pivot->weight,
            'instructors'         => InstructorBasicInfoResource::collection($this->instructors),
            'instructor'          => new InstructorBasicInfoResource($this->user),
            'intro_video'         => $this->intro_video,
            'is_free'             => $this->is_free,
            'slug_url'       => $this->slug_url,
            'tags'          => $this->tags()->active()->get()->map(function ($row){return $row->name;})

        ];


    }
    private function getLearnPaths(){
        $subscription_ids =  $this->active_subscription->pluck('package_id')->toArray();
        return PackageSubscription::whereIn('id', $subscription_ids)->get();
    }
}
