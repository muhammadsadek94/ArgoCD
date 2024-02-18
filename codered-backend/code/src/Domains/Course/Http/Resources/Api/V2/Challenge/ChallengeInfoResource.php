<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Challenge;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use Carbon\Carbon;

class ChallengeInfoResource extends JsonResource
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
            'id'                    => $this->id,
            'slug'                  => $this->slug,
            'name'                  => $this->name,
            'challenge_id'          => $this->competition_id,
            'started'               => $user ? $user->competitions()->where('competition_id', $this->competition_id)->exists() : false,
            'image'                 => new FileResource($this->whenLoaded('image')),
            'duration'              => round((int)$this->duration / 60, 1),
            'end_date'              => Carbon::parse($this->end_date)->format('F Y'),
            'description'           => $this->description,
            'competition_scenario'  => $this->competition_scenario,
            'tags'                  => $this->tags,
            'flags'                 => $this->flags,
        ];
    }

}
