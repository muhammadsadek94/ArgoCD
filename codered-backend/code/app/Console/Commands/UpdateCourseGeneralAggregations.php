<?php

namespace Framework\Console\Commands;

use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Course;
use App\Domains\Payments\Models\LearnPathInfo;
use Illuminate\Console\Command;

class UpdateCourseGeneralAggregations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:update-general-aggregations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loop through all courses and update the ordinary aggregations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        ini_set('memory_limit', '1G');

        $this->info("Fetching Courses");

        $courses = Course::whereIn('activation', [CourseActivationStatus::ACTIVE, CourseActivationStatus::HIDDEN])
            ->withCount('course_enrollments')
            ->withCount('reviews')
            ->withAvg('reviews', 'rate')
            ->withCount('chapters')
            ->where('updated_at', '<', now()->subHours(3))
            ->get();

        $this->info("Fetched {$courses->count()} Course");

        $this->withProgressBar($courses, function (Course $course) {
            foreach ($course->chapters as $chapter) {
                $chapter_lessons = $chapter->lessons()->where('type', '!=', LessonType::CHECKPOINT)->active()->get();
                $chapter_lessons_duration = $chapter_lessons->sum('time');
                $chapter->update([
                    'agg_lessons' => [
                        'total_lessons'       => $chapter_lessons->count(),
                        'total_videos'        => $chapter_lessons->where('type', LessonType::VIDEO)->count(),
                        'total_quizzes'       => $chapter_lessons->where('type', LessonType::QUIZ)->count(),
                        'total_labs'          => $chapter_lessons->whereIn('type', [LessonType::LAB, LessonType::CYPER_Q])->count(),
                        'duration'            => $chapter_lessons_duration,
                        'duration_human_text' => $this->getDuration($chapter_lessons_duration),

                    ]
                ]);
            }

            $course_lessons = $course->lessons()->where('type', '!=', LessonType::CHECKPOINT)->active()->get();
            $course_lessons_duration = $course_lessons->sum('time');
            $course->update([
                'agg_avg_reviews'             => round($course->reviews_avg_rate, 1),
                'agg_count_reviews'           => $course->reviews_count,
                'agg_count_course_enrollment' => $course->course_enrollments_count,

                'agg_count_course_chapters' => $course->chapters_count,
                'agg_lessons'               => [
                    'total_lessons'       => $course_lessons->count(),
                    'total_videos'        => $course_lessons->where('type', LessonType::VIDEO)->count(),
                    'total_quizzes'       => $course_lessons->where('type', LessonType::QUIZ)->count(),
                    'total_labs'          => $course_lessons->whereIn('type', [LessonType::LAB, LessonType::CYPER_Q])->count(),
                    'duration'            => $course_lessons_duration,
                    'duration_human_text' => $this->getDuration($course_lessons_duration),
                ],
                'agg_reviews' => $this->getRatePercentage($course)
            ]);
        });

        $this->info("{$courses->count()} Course Updated");


        $this->info("Fetching Learning Paths");

        $learning_paths = LearnPathInfo::where('updated_at', '<', now()->subHours(3))->get();

        $this->info("Fetched {$learning_paths->count()} Learning Path");

        $this->withProgressBar($learning_paths, function (LearnPathInfo $learning_path) {

            $total_lessons = 0;
            $total_videos = 0;
            $total_quizzes = 0;
            $total_labs = 0;
            $duration = 0;

            $total_lessons = array_sum($learning_path->allCourses->map(function ($course) {
                return $course->agg_lessons ? $course->agg_lessons['total_lessons'] : 0;
            })->toArray());

            $total_quizzes = array_sum($learning_path->allCourses->map(function ($course) {
                return $course->agg_lessons ? $course->agg_lessons['total_quizzes'] : 0;
            })->toArray());

            $total_videos = array_sum($learning_path->allCourses->map(function ($course) {
                return $course->agg_lessons ? $course->agg_lessons['total_videos'] : 0;
            })->toArray());

            $total_labs = array_sum($learning_path->allCourses->map(function ($course) {
                return $course->agg_lessons ? $course->agg_lessons['total_labs'] : 0;
            })->toArray());

            $duration = array_sum($learning_path->allCourses->map(function ($course) {
                return $course->agg_lessons ? $course->agg_lessons['duration'] : 0;
            })->toArray());

            $duration_human_text = $duration ? $this->getDuration($duration) : "";

            $learning_path->update([
                'agg_courses' => [
                    'total_courses'       => $learning_path->courses()->count(),
                    'total_lessons'       => $total_lessons,
                    'total_quizzes'       => $total_quizzes,
                    'total_videos'        => $total_videos,
                    'total_labs'          => $total_labs,
                    'duration'            => $duration,
                    'duration_human_text' => $duration_human_text,
                ]
            ]);

        });

        $this->info("{$learning_paths->count()} Learning Path Updated");

        return Command::SUCCESS;
    }

    public function getDuration($duration)
    {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        if ($duration < 60)
            $minutes = 1;
        if ($hours)
            return $hours . ' hrs ' . $minutes . ' mins';
        return $minutes . ' mins';
    }

    private function getRatePercentage($course): array
    {
        $rates = [];

        $reviews = $course->reviews;
        $count = $reviews->count();
        if ($count == 0) return [];
        for ($i = 5; $i > 0; $i--) {
            $reviews = $course->reviews;
            array_push($rates, [
                'value' => $i,
                'percentage' => round((($reviews->where('rate', $i)->count() / $count) * 100), 0) . '%',
            ]);
        }
        return $rates;
    }
}
