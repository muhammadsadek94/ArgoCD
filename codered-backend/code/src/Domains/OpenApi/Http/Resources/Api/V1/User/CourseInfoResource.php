<?php
namespace App\Domains\OpenApi\Http\Resources\Api\V1\User;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V1\CourseCategoryResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class CourseInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'brief'                 => $this->brief,
            'level'                 => $this->level,
            'timing'                => $this->timing ? round($this->timing / 60, 2) : 0,
            'course_type'           => $this->course_type,
            'image'                 => new FileResource($this->whenLoaded('image')),
            'category'              => new CourseCategoryResource($this->whenLoaded('category')),
            'enrolled'              => $request->user('api') ? $request->user('api')->course_enrollments()->where('course_id', $this->id)->count() > 0 : false,
            'completion_percentage' => $this->when($request->user('api'), function () use ($request) {
                $total_lessons = $this->lessons()->where(['type' => LessonType::VIDEO])->active()->whereHas('chapter', function ( $query) {
                    $query->active();
                })->count();
                $completed_lessons = $request->user('api')->watched_lessons()
                    ->where(['lessons.course_id' => $this->id, 'type' => LessonType::VIDEO])
                    ->count();
                if ($total_lessons == 0) return 0;
                $percentage = $completed_lessons / $total_lessons * 100;

                return $percentage > 100 ? 100 : $percentage;
            }),
            'is_free'               => $this->is_free,
            'url' => config('user.user_website') . "/course/{$this->id}",
            'slug_url'       => $this->slug_url,

        ];
    }
}
