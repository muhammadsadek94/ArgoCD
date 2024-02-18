<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Microdegree;

use App\Domains\Uploads\Http\Resources\FileResource;
use App\Foundation\Traits\Authenticated;
use INTCore\OneARTFoundation\Http\JsonResource;

class InternalMicrodegreeResource extends JsonResource
{
    use Authenticated;
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->auth('api');
        $completePrecentage = $this->completedPercentage($user);

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'brief'          => $this->brief,
            'is_featured'    => $this->is_featured,
            'estimated_time' => $this->microdegree ? $this->microdegree->estimated_time : null,
            'image'          => new FileResource($this->image),
            'count_lessons'  => $this->lessons()->count(),
            'slug_url'       => $this->slug_url,
            'slack_url'      => $this->slack_url,
            'timing'         => $this->timing ? round($this->timing / 60, 2) : 0
        ];
    }
}
