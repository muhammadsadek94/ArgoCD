<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Microdegree;

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
        $user_completion_percentage = $user ? $this->completedPercentageLoad->sortByDesc('completed_percentage')->first() : null;
        $completed_percentage = 0;
        if ($this->lessons_count && !$user_completion_percentage && $user) {
            $completed_percentage = ($user->watched_lessons->where('course_id', $this->id)->count() / $this->lessons_count * 100);
        }

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'is_featured'           => $this->is_featured,
            'estimated_time'        => $this->microdegree ? $this->microdegree->estimated_time : 0,
            'image'                 => new FileResource($this->image),
            'count_lessons'         => $this->lessons_count,
            'slug_url'              => $this->slug_url,
            'completion_percentage' => $user ? round($user_completion_percentage?->completed_percentage ?? $completed_percentage) : 0,
            'enrolled'              => $user ? $user->microdegree_certifications_enrollments->where('id', $this->id)->count() > 0 : false,
            'finished'              => $user ? $user->completed_courses->where('id', $this->id)->count() > 0 : false,
        ];
    }
}
