<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Lesson;

use App\Domains\Comment\Http\Resources\Api\V2\User\CommentResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class ProjectApplicationResource extends JsonResource
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
            'id'   => $this->id,
            'url'   => $this->url,
            'status' => $this->status,
            'comment'   => CommentResource::collection( $this->comments),
        ];
    }

}
