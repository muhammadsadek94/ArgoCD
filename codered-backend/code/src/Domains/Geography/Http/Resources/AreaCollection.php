<?php

namespace App\Domains\Geography\Http\Resources;

use INTCore\OneARTFoundation\Http\ResourceCollection;

class AreaCollection extends ResourceCollection
{
    /**
    * json parser
    */
     public $collects = AreaResource::class;

    public $isPaginated = false;

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
