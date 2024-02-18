<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use INTCore\OneARTFoundation\Http\ResourceCollection;

class EnterpriseLearnPathIndexCollection extends ResourceCollection
{
    /**
    * json parser
    */
     public $collects = EnterpriseLearnPathsIndexResource::class;

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
