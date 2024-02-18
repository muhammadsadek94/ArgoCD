<?php

namespace App\Domains\Notification\Http\Resources;

use INTCore\OneARTFoundation\Http\ResourceCollection;

class GetMyNotificationCollection extends ResourceCollection
{
    /**
    * json parser
    */
     public $collects = GetMyNotificationResource::class;

    public $isPaginated = true;

    public $dataHolder = 'notifications';

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
