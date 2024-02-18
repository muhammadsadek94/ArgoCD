<?php

namespace App\Domains\Course\Http\Resources\Api\V1\Lesson;

use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CourseEnrollment;
use Illuminate\Http\Request;
use App\Domains\Favourite\Enum\FavouriteType;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Uploads\Http\Resources\FileResource;
use App\Foundation\Traits\Authenticated;
use Carbon\Carbon;

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
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'timing'     => $this->time,
            'type'       => $this->type,
            'is_watched' => $request->user('api')->watched_lessons()->where('lesson_id', $this->id)->count() > 0,
            'overview'   => $this->overview,
            'video'      => $this->video ? $this->getVideoData($this->video) : null,
            'quiz'       => LessonQuizResource::collection($this->mcq),
            'resources'  => LessonAttachmentsResource::collection($this->resources),
            'faq'        => LessonFaqResource::collection($this->faq),
            'notes'      => LessonNoteResource::collection($this->notes()->where('user_id', $request->user('api')->id)->orderBy('created_at')->get()),
            'is_fav'     => $this->isFav($request),
            'image'      => new FileResource($this->image),
            'manual'      => new FileResource($this->manual),
            'lesson_objectives' => $this->lesson_objectives()->active()->orderBy('sort')->get()->map(function ($row) {
                return $row->objective_text;
            }),
            'lesson_tasks'     => LessonTaskResource::collection($this->lesson_tasks()->active()->orderBy('sort')->get()),
            'drip_content' => $this->drip_time($this)
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
        $enrollment_time = CourseEnrollment::where('course_id', $lesson->course_id)->where('user_id', $user->id)->first()->created_at;
        $chapter = Chapter::where('id', $lesson->chapter_id)->first();
        if (Carbon::now() > $enrollment_time->addDays($chapter->drip_time))
            return true;
        else
            return false;
    }
}
