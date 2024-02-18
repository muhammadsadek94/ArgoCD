<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Events\Course\ChapterCompleted;
use App\Domains\Course\Events\Course\CourseCompleted;
use App\Domains\Course\Events\Course\GenerateCertificate;
use App\Domains\Course\Events\Course\LessonCompleted;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\WatchedLesson;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Jobs\Api\V1\User\AddToWatchHistoryJob;
use App\Domains\Course\Jobs\Api\V1\User\MarkLessonAsWatchedJob;
use App\Domains\Course\Models\Lesson;

class MarkLessonAsWatchedFeature extends Feature
{

    public function handle(Request $request)
    {
        $user = $request->user('api');
        $lesson_id = $request->lesson_id;
        $lesson = Lesson::find($lesson_id)->load('chapter', 'course');
        $course = $lesson->course;

        $isAlreadyWatched = WatchedLesson::where([
            'lesson_id'  => $lesson_id,
            'chapter_id' => $lesson->chapter_id,
            'course_id'  => $lesson->course_id,
            'user_id'    => $user->id
        ])->exists();


            $this->run(MarkLessonAsWatchedJob::class, [
                'user'   => $user,
                'lesson' => $lesson
            ]);

            $this->run(AddToWatchHistoryJob::class, [
                'user'   => $request->user(),
                'lesson' => $lesson
            ]);

            $lastCompletedCourse = CompletedCoursePercentage::where([
                'user_id'   => $user->id,
                'course_id' => $lesson->course_id
            ])->first();

            if ($lastCompletedCourse) {
                $lastCompletedCourse->completed_percentage = $course->completedPercentageCalcualate($user);
                $lastCompletedCourse->is_finished = $lastCompletedCourse->completed_percentage == 100;
                $lastCompletedCourse->save();
            } else {
                CompletedCoursePercentage::firstOrCreate([
                    'user_id'              => $user->id,
                    'course_id'            => $course->id,
                    'completed_percentage' => $course->completedPercentageCalcualate($user),
                    'is_finished' => false
                ]);
            }
        if (!$isAlreadyWatched) {

            $this->isCourseFinishedToSendActiveCampaign($course, $user);
            $this->isChapterFinished($lesson->chapter, $user);
            event(new LessonCompleted($lesson, $user));
        }
        $this->isCourseFinishedToCreateCertificate($course, $user);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'status' => true
            ]
        ]);
    }

    private function isCourseFinishedToCreateCertificate(Course $course, User $user)
    {

        if ($course->course_type == CourseType::COURSE) {
            $is_finished = $course->isCourseFinished($user);
            if($is_finished){
                event(new GenerateCertificate($course, $user));
            }
        }

    }

    private function isCourseFinishedToSendActiveCampaign(Course $course, User $user)
    {
        $is_finished = $course->isCourseFinished($user);
        if($is_finished){
            event(new CourseCompleted($course, $user));
        }
    }


    private function isChapterFinished(Chapter $chapter, User $user)
    {
        $count_video_lessons = $chapter->lessons()
            ->where('type', LessonType::VIDEO)
            ->active()->count();

        $count_watched_lessons = WatchedLesson::where([
            'chapter_id' => $chapter->id,
            'user_id'    => $user->id
        ])->count();

        if ($count_watched_lessons >= $count_video_lessons) {
            event(new ChapterCompleted($chapter, $user));
        }
    }

}
