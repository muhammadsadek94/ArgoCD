<?php

namespace App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo;

use App\Domains\Payments\Http\Resources\Api\V2\LearnPathInfo\LearnPathBasicInfoResource;
use INTCore\OneARTFoundation\Http\ResourceCollection;

class LearnPathCollection extends ResourceCollection
{
    /**
    * json parser
    */
     public $collects = LearnPathBasicInfoResource::class;

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
