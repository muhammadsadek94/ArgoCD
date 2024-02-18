<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Microdegree;

use App\Domains\Course\Enum\CoursePackageType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Http\Resources\Api\V2\Microdegree\PackageResource;
use App\Domains\Course\Models\Chapter;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Domains\User\Http\Resources\Api\V2\User\InstructorBasicInfoResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class MicrodegreeInformationResource extends JsonResource
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
            'id'                        => $this->id,
            'name'                      => $this->name,
            'brief'                     => $this->brief,
            'intro_video'               => $this->intro_video,
            'is_featured'               => $this->is_featured,
            'estimated_time'            => $this->microdegree ? $this->microdegree->estimated_time : 0,
            'image'                     => new FileResource($this->image),
            'cover'                     => new FileResource($this->cover),
            'syllabus_url'              => $this->microdegree ? $this->microdegree->syllabus_id : null,
            'learn'                     => $this->learn,
            'prerequisites'             => $this->microdegree ? $this->microdegree->prerequisites : [],
            'project'                   => $this->microdegree ? $this->microdegree->project : [],
            'skills_covered'             => $this->microdegree ? $this->microdegree->skills_covered : [],
            'key_features'             => $this->microdegree ? $this->microdegree->key_features : [],
            'average_salary'            => $this->microdegree ? $this->microdegree->average_salary : 0,
            'faq'                       => $this->microdegree ? $this->microdegree->faq : [],
            'slack_url'                 => $this->microdegree ? $this->microdegree->slack_url : '',
            'instructors'               => InstructorBasicInfoResource::collection($this->instructors),
            'packages'                  => new PackageResource($this->packages()->where('type', CoursePackageType::ONE_TIME)->first()),
            'packages2'                 => PackageResource::collection($this->packages),
            'slug_url'                  => $this->slug_url,
            'what_you_will_learn'       => $this->course_learns ?? CourseLearnResource::collection($this->course_learns),
            'price'                     => $this->price,
            'labs_count'                => $this->getLabsCount(),
            'videos_count'              => $this->getVideosCount(),
            'assessment_questions'      => $this->getAssessmentQuestions(),
        ];
    }


    private function getLabsCount()
    {
        $course_id = $this->id;
        $count = Chapter::where('course_id', $course_id)->active()->with(['lessons' => function($query) {
            return $query->where('type', LessonType::LAB);
        }])->get()->filter(function ($item) {
            return $item->lessons && $item->lessons->count();
        })->count();

        return $count;
    }

    private function getVideosCount()
    {
        $course_id = $this->id;
        $chapters = Chapter::where('course_id', $course_id)->active()->withCount(['lessons' => function($query){
            $query->where('type', LessonType::VIDEO);
        }])->get()->sum('lessons_count');
        return $chapters;
    }

    private function getAssessmentQuestions()
    {
        $course_id = $this->id;
        $chapters = Chapter::active()->where('course_id', $course_id)->withCount(['lessons' => function($query){
            $query->where('type', LessonType::QUIZ)->active();
        }])->get()->sum('lessons_count');
        return $chapters;
    }

}
