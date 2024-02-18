<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Lesson;

use App\Domains\Course\Http\Resources\Api\V2\CourseBasicInfoResource;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CourseEnrollment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Foundation\Traits\Authenticated;
use App\Domains\Favourite\Enum\FavouriteType;
use INTCore\OneARTFoundation\Http\JsonResource;

class LessonTinyInformationResource extends JsonResource
{
    use Authenticated;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'timing'            => $this->time,
            'type'              => $this->type,
            'chapter_id'        => $this->chapter_id,
            'sort'              => $this->sort,
            'course_slug_url'   => $this->course->slug_url,
        ];

    }

}
