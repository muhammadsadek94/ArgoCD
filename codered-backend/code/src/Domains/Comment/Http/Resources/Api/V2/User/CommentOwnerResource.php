<?php

namespace App\Domains\Comment\Http\Resources\Api\V2\User;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CommentOwnerResource extends JsonResource
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
            'name'       =>isset( $this->first_name) ? $this->first_name : $this->name,
//            'email'       =>$this->email,
            "image" => new FileResource($this->image)
        ];
    }

}
