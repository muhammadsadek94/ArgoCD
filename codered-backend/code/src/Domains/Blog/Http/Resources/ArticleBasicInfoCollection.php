<?php

namespace App\Domains\Blog\Http\Resources;

use INTCore\OneARTFoundation\Http\ResourceCollection;

class ArticleBasicInfoCollection extends ResourceCollection
{
    /**
    * json parser
    */
     public $collects = ArticleBasicInfoResource::class;

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
