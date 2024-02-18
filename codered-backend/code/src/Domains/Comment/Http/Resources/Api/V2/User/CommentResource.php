<?php

namespace App\Domains\Comment\Http\Resources\Api\V2\User;

use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;

class CommentResource extends JsonResource
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
            'id'        => $this->id,
            'comment'   => $this->comment,
            'entity_id'   => $this->entity_id,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i A'),
            'owner'     => new CommentOwnerResource($this->owner)
        ];
    }

}
