<?php

namespace App\Domains\Course\Http\Resources\Api\V2\Lesson;

use Illuminate\Http\Request;
use App\Domains\Favourite\Enum\FavouriteType;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Foundation\Traits\Authenticated;
use Carbon\Carbon;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CourseEnrollment;

class LessonInformationResource extends JsonResource
{
    use Authenticated;


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $request->user('api');
        $course = $this->course;
        $lesson = $this->resource;

        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'timing'     => $this->time,
            'chapter_id' => $this->chapter_id,
            'course_id'  => $this->course_id,
            'type'       => $this->type,
            'is_watched' => $request->user('api')->watched_lessons()->where('lesson_id', $this->id)->count() > 0,
            'overview'   => $this->overview,
            'video'      => $this->video ? $this->getVideoData($this->video) : null,
            'quiz'       => LessonQuizResource::collection($this->mcq),
            'resources'  => LessonAttachmentsResource::collection($this->resources),
            'faq'        => LessonFaqResource::collection($this->faq),
            'notes'      => LessonNoteResource::collection($this->notes->where('user_id', $user?->id)),
            'is_fav'     => $this->isFav($request),
            'image'      => new FileResource($this->image),
            'manual'      => new FileResource($this->manual),
            'lesson_objectives' => $this->lesson_objectives()->active()->orderBy('sort')->get()->map(function ($row) {
                return $row->objective_text;
            }),
            'project'    => new ProjectApplicationResource($this->project()->where('user_id', $user?->id)->first()),
            'is_available'  => lesson_access_permission($lesson),

            'lesson_tasks'     => LessonTaskResource::collection($this->lesson_tasks),
            'drip_content' => $this->drip_time($this),
            'chapter_number' => $this->chapter->sort,
            "outer_overview" =>$this->outer_overview,

        ];
    }

    private function getVideoData($video)
    {
        return [
            'account_id' => @$video['account_id'],
            'player_id'  => @$video['player_id'],
            'video_id'   => @$video['video_id'],
            'type'   => @$video['type'],
        ];
    }

    private function isFav(Request $request)
    {
        if (is_null($this->auth())) return false;

        return $this->auth()->favourite_lessons()->where([
            'favourable_id'   => $this->id,
            'favourable_type' => FavouriteType::LESSON
        ])->count() > 0;
    }


    private function  drip_time($lesson)
    {
        $user = auth()->guard('api')->user();
        $CourseEnrollment = $this->course?->course_enrollments?->where('user_id', $user->id)->first();
        // check for free lesson as user may not be enrolled yet
        if ($CourseEnrollment) {
            $enrollment_time = $this->course?->course_enrollments?->where('user_id', $user->id)->first()->created_at;
        } else
            return true;

        $chapter = Chapter::where('id', $lesson->chapter_id)->first();
        if (Carbon::now() > $enrollment_time->addDays($chapter->drip_time))
            return true;
        else
            return false;
    }
}
