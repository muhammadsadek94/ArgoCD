<?php

namespace App\Domains\Partner\Http\Resources\Api\V2;

use INTCore\OneARTFoundation\Http\ResourceCollection;

class CourseCollection extends ResourceCollection
{
    /**
    * json parser
    */
     public $collects = CourseResource::class;

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
