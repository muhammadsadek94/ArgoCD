<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Models\Lesson;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Course\Models\LessonUserNote;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonNoteResource;


class AddNoteFeature extends Feature
{

    public function handle(Request $request)
    {

        // TODO : move code to job
        // TODO : validation
        $lesson = Lesson::find($request->lesson_id);
        $note = LessonUserNote::create([
            'user_id'   => $request->user('api')->id,
            'lesson_id' => $request->lesson_id,
            'chapter_id' => $lesson->chapter_id,
            'course_id' => $lesson->course_id,
            'note'      => $request->note,
            'title'     => $request->title
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'note' => new LessonNoteResource($note),
            ]
        ]);
    }
}
