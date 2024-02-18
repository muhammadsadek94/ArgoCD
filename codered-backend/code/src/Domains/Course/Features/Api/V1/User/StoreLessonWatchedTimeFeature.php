<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Events\Course\ChapterCompleted;
use App\Domains\Course\Events\Course\CourseCompleted;
use App\Domains\Course\Events\Course\LessonCompleted;
use App\Domains\Course\Jobs\Api\V1\User\StoreLessonWatchedTimeJob;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Jobs\Api\V1\User\AddToWatchHistoryJob;
use App\Domains\Course\Jobs\Api\V1\User\MarkLessonAsWatchedJob;
use App\Domains\Course\Models\Lesson;

class StoreLessonWatchedTimeFeature extends Feature
{

    public function handle(Request $request)
    {
        $user = $request->user('api');
        $lesson_id = $request->lesson_id;
        $watched_time = $request->watched_time;

        $lesson = Lesson::findOrFail($lesson_id)->load('chapter', 'course');
        $course = $lesson->course;
        $quarter = $this->getQuarter();
        $start_date = date('Y-m-d', strtotime($quarter['start_date']));
        $end_date = date('Y-m-d', strtotime($quarter['end_date']));
        $isAlreadyWatched = WatchHistoryTime::where([
            'lesson_id' => $lesson_id,
            'course_id' => $lesson->course_id,
            'status' => false,
            'user_id' => $user->id,
//            'created_at', '>=', date('Y-m-d', strtotime( $quarter['start_date'])),
//            'created_at', '<=',date('Y-m-d',strtotime ($quarter['end_date'])),
        ])->whereBetween('created_at', [$start_date,$end_date])->first();

        if ($isAlreadyWatched) {
            $isAlreadyWatched->watched_time = $isAlreadyWatched->watched_time + $watched_time;
            $isAlreadyWatched->save();
        } else {
            $this->run(StoreLessonWatchedTimeJob::class, [
                'user' => $user,
                'lesson' => $lesson,
                'watched_time' => $watched_time
            ]);
        }


        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }


    private function getQuarter()
    {
        $selected_quarter_index = 0;
        $quarters = [
            0 => [
                'start_date' => '01/01/{{year}}',
                'end_date' => '03/31/{{year}}',
            ],
            1 => [
                'start_date' => '04/01/{{year}}',
                'end_date' => '06/30/{{year}}',
            ],
            2 => [
                'start_date' => '07/01/{{year}}',
                'end_date' => '09/30/{{year}}',
            ],
            3 => [
                'start_date' => '10/01/{{year}}',
                'end_date' => '12/31/{{year}}',
            ]
        ];

        foreach ($quarters as $quarter) {
            $start_date = str_replace('{{year}}', date('Y'), $quarter['start_date']);
            $end_date = str_replace('{{year}}', date('Y'), $quarter['end_date']);
            $DateBegin = date('Y-m-d', strtotime($start_date));
            $DateEnd = date('Y-m-d', strtotime($end_date));
            $currentDate = date('Y-m-d');
            if (($currentDate >= $DateBegin) && ($currentDate <= $DateEnd)) {
                break;
            }
            $selected_quarter_index = $selected_quarter_index + 1;
        }
        return str_replace('{{year}}', date('Y'), $quarters[$selected_quarter_index]);


    }
}
