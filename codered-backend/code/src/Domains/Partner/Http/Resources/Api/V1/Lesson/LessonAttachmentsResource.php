<?php

namespace App\Domains\Partner\Http\Resources\Api\V1\Lesson;

use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;

class LessonAttachmentsResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'attachment'     => new FileResource($this->attachment),
        ];
    }

}
