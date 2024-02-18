<?php

namespace App\Domains\Course\Http\Resources\Api\V1;

use App\Domains\Course\Models\CompletedCourses;
use Illuminate\Http\Request;
use Str;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V1\User\InstructorBasicInfoResource;
use App\Domains\Course\Http\Resources\Api\V1\Chapter\ChapterBasicInfoResource;

class CourseInformationResource extends JsonResource
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
            'id'                  => $this->id,
            'name'                => $this->name,
            'activation'          => $this->activation,
            'image'               => new FileResource($this->image),
            'cover'               => new FileResource($this->cover),
            'category'            => new CourseCategoryResource($this->category),
            'brief'               => $this->brief,
            'description'         => $this->description,
            'level'               => $this->level,
            'timing'              => $this->timing,
            'learn'               => $this->learn,
            'course_syllabus'     => ChapterBasicInfoResource::collection($this->chapters()->has('lessons')->active()->get()),
            'instructors'         => InstructorBasicInfoResource::collection($this->instructors),
            'instructor'          => new InstructorBasicInfoResource($this->user),
            'enrolled'             => $request->user('api') ? $request->user('api')->course_enrollments()->where('course_id', $this->id)->count() > 0 : false,
            'intro_video'         => $this->intro_video,
            'completed_course_id' => $this->getCompletedCourseId($request),
            'is_free'             => $this->is_free,
            'slug_url'       => $this->slug_url,
            'tags'          => $this->tags()->active()->get()->map(function ($row){return $row->name;})

        ];
    }

    private function getCompletedCourseId($request)
    {
        $user = $request->user('api');

        if (!$user) return null;

        return CompletedCourses::where([
                'user_id'   => $user->id,
                'course_id' => $this->id
            ])->first()->id ?? null;

    }
}
