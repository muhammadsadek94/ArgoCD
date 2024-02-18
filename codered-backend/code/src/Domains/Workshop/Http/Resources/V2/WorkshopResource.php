<?php

namespace App\Domains\Workshop\Http\Resources\V2;

use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V1\User\UserBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class WorkshopResource extends JsonResource
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
            'id'                => $this->id,
            'title'             => $this->title,
            'instructor'        => new UserBasicInfoResource($this->user),
            'description'       => $this->description,
            'date'              => $this->getDateConverted($this->date),
            'time'              => $this->getTimeConverted($this->time),
            'link'              => $this->link,
            'image'             => new FileResource($this->image),
        ];
    }


}
