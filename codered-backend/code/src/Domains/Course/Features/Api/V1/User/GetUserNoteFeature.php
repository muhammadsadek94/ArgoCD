<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Enum\LessonActivationStatus;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Models\LessonUserNote;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonNoteResource;

class GetUserNoteFeature extends Feature
{

    public function handle(Request $request)
    {
        $notes = LessonUserNote::where([
            'user_id' => $request->user('api')->id,
        ])->with('lesson', 'course')
        ->whereHas('course', function($query){
            $query->where('activation', CourseActivationStatus::ACTIVE);
        })
        ->whereHas('lesson', function($query){
            $query->where('activation', LessonActivationStatus::ACTIVE);
        })
        ->latest()->get();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'note' => LessonNoteResource::collection($notes),
            ]
        ]);
    }
}
