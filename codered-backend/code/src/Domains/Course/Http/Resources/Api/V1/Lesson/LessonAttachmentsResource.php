<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Lesson;

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
        'type'       => (int)$this->type,
        'link'       => $this->link,
        'attachment'     => new FileResource($this->attachment),
        ];
    }

}
