<?php
namespace App\Domains\OpenApi\Http\Resources\Api\V2\User;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V2\CourseCategoryResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use INTCore\OneARTFoundation\Http\ResourceCollection;

class CourseInfoResourceCollection extends ResourceCollection
{
    public $collects = CourseInfoResource::class;

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
