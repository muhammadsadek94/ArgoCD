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

class FreeLessonInformationResource extends JsonResource
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
            'chapter_id' => $this->chapter_id,
            'course_id'  => $this->course_id,
            'type'       => $this->type,
            'free'       => $this->is_free,
            'video'      => $this->video ? $this->getVideoData($this->video) : null,
            'image'      => new FileResource($this->image),
            'manual'      => new FileResource($this->manual),
            'lesson_objectives' => $this->lesson_objectives()->active()->orderBy('sort')->get()->map(function ($row) {
                return $row->objective_text;
            }),

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


}
