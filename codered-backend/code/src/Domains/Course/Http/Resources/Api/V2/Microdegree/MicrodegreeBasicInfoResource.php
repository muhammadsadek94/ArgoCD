<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Microdegree;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class MicrodegreeBasicInfoResource extends JsonResource
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
        $all_course_enrollments = $user->all_course_enrollments;
        $completion_percentage = $this->completedPercentageLoad->first();


        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'is_featured'           => $this->is_featured,
            'estimated_time'        => $this->microdegree ? $this->microdegree->estimated_time : null,
            'image'                 => new FileResource($this->image),
            'count_lessons'         => $this->lessons_count,
            'slug_url'              => $this->slug_url,
            'completion_percentage' => $user ? round($completion_percentage?->completed_percentage) : 0,
            'finished'              => $user ? !!$completion_percentage?->is_finished : false,
            'enrolled'              => $user ? $all_course_enrollments->where('id', $this->id)->count() > 0 : false,
            'timing'                => $this->timing ? round($this->timing / 60, 2) : 0
        ];
    }
}
