<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use INTCore\OneARTFoundation\Http\ResourceCollection;

class CourseCollection extends ResourceCollection
{
    /**
    * json parser
    */
     public $collects = CourseBasicInfoResource::class;

    public $isPaginated = true;

    public $dataHolder = 'data';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
