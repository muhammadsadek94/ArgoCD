<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Menu;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Enterprise\Enum\LearnPathsDeadlineType;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Models\UserSubscription;
use Auth;
use Carbon\Carbon;
use INTCore\OneARTFoundation\Http\JsonResource;

class LearnPathBasicResource extends JsonResource
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
            'id'       => $this->id,
            'name'     => $this->name,
            'slug_url' => $this->slug_url,
        ];

    }

}
