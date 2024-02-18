<?php

namespace App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo;

use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Uploads\Http\Resources\FileResource;
use Auth;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;

class LearnPathInfoResource extends JsonResource
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
            'id'                        => $this->id,
            'name'                      => $this->name,
            'slug_url'                  => $this->slug_url,
            'description'               => $this->description,
            'price'                     => $this->price,
            'duration'                  => $this->package_subscription ?  $this->getDuration($user) : 0,
            'image'                     => new FileResource($this->image),
            'cover'                     => new FileResource($this->cover),
            'type'                      => (int) $this->type,
            'type_name'                 => LearnPathType::getTypeName((int) $this->type),
            'features'                  => $this->features,
            'courses_count'             => $this->allCourses->count(),
            'completed_courses_count'   => $this->allCourses->count() ? $this->completedCoursesCount($user) : 0,
            'completion_percentage'     => $this->allCourses->count() ? $this->completionPercentage($user) : 0,
            'metadata'=> $this->metadata ? $this->metadata : [],
            'timing'                    => $this->getVideosDuration(),

        ];
    }

    private function completedCoursesCount($user)
    {
        if (!$user) return 0;
        return $this->completedCourses->count();
    }

    private function completionPercentage($user)
    {
        if (!$user) return 0;
        return round($this->completedCourses->count() / $this->allCourses->count() * 100);
    }

    private function getDuration($user) {
        if(!$user) return;
        $subsription = $this->user_subscriptions->first();
        if($subsription)
            return Carbon::parse($subsription->expired_at)->format('d M Y');
    }

    private function getVideosDuration()
    {
        $timing = $this->allCourses->sum('timing');
        $duration = ceil($timing / 60);
        return $duration;
    }

}
