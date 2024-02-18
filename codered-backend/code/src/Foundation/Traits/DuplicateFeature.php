<?php

namespace App\Foundation\Traits;

use App\Domains\Course\Enum\LessonType;
use Carbon\Carbon;
use DB;

trait DuplicateFeature
{

    public function duplicate()
    {
        $course = $this->model->find($this->request->course_id);

        $course->load('microdegree', 'assessments', 'chapters', 'chapters.lessons', 'image', 'packages', 'course_learns', 'tags', 'jobRoles', 'specialtyAreas', 'instructors');

        $newCourse = $course->replicate();
        $newCourse->name = 'Copy - ' . $course->name;
        $newCourse->created_at = Carbon::now();
        $newCourse->push();

        $newMicrodegree = $course->microdegree->replicate();
        $newMicrodegree->course_id = $newCourse->id;
        $newMicrodegree->push();


        foreach($course->chapters as $chapter) {
            $newChapter = $chapter->replicate();
            $newChapter->course_id = $newCourse->id;
            $newChapter->created_at = Carbon::now();
            $newChapter->push();

            foreach($chapter->lessons()->where('type', '!=', LessonType::QUIZ)->get() as $lesson) {

                $newLesson = $lesson->replicate();
                $newLesson->chapter_id = $newChapter->id;
                $newLesson->course_id = $newCourse->id;
                $newLesson->created_at = Carbon::now();
                $newLesson->push();

                foreach($lesson->resources as $resource) {
                    $newResource = $resource->replicate();
                    $newResource->lesson_id = $newLesson->id;
                    $newResource->created_at = Carbon::now();
                    $newResource->push();
                }

                foreach($lesson->mcq as $mcq) {
                    $newMcq = $mcq->replicate();
                    $newMcq->lesson_id = $newLesson->id;
                    $newMcq->related_lesson_id = null;
                    $newMcq->created_at = Carbon::now();
                    $newMcq->push();
                }

                foreach($lesson->faq as $faq) {
                    $newFaq = $faq->replicate();
                    $newFaq->lesson_id = $newLesson->id;
                    $newFaq->created_at = Carbon::now();
                    $newFaq->push();
                }

                foreach($lesson->lesson_objectives as $objective) {
                    $newObjective = $objective->replicate();
                    $newObjective->lesson_id = $newLesson->id;
                    $newObjective->chapter_id = $newChapter->id;
                    $newObjective->course_id = $newCourse->id;
                    $newObjective->created_at = Carbon::now();
                    $newObjective->push();
                }

                foreach($lesson->lesson_tasks as $task) {
                    $newTask = $task->replicate();
                    $newTask->lesson_id = $newLesson->id;
                    $newTask->chapter_id = $newChapter->id;
                    $newTask->course_id = $newCourse->id;
                    $newTask->created_at = Carbon::now();
                    $newTask->push();
                }

            }

            foreach($chapter->lessons()->where('type', LessonType::QUIZ)->get() as $lesson) {

                $newLesson = $lesson->replicate();
                $newLesson->chapter_id = $newChapter->id;
                $newLesson->course_id = $newCourse->id;
                $newLesson->created_at = Carbon::now();
                $newLesson->push();

                foreach($lesson->resources as $resource) {
                    $newResource = $resource->replicate();
                    $newResource->lesson_id = $newLesson->id;
                    $newResource->created_at = Carbon::now();
                    $newResource->push();
                }

                foreach($lesson->mcq as $mcq) {
                    $new_related_lesson = null;
                    if($mcq->relatedLesson) {
                        $old_related_lesson_sort = $mcq->relatedLesson->sort;
                        $new_related_lesson = $newChapter->lessons()->where('sort', $old_related_lesson_sort)->first();
                    }
                    $newMcq = $mcq->replicate();
                    $newMcq->lesson_id = $newLesson->id;
                    $newMcq->related_lesson_id = $new_related_lesson ? $new_related_lesson->id : null;
                    $newMcq->created_at = Carbon::now();
                    $newMcq->push();
                }

                foreach($lesson->faq as $faq) {
                    $newFaq = $faq->replicate();
                    $newFaq->lesson_id = $newLesson->id;
                    $newFaq->created_at = Carbon::now();
                    $newFaq->push();
                }

                foreach($lesson->lesson_objectives as $objective) {
                    $newObjective = $objective->replicate();
                    $newObjective->lesson_id = $newLesson->id;
                    $newObjective->chapter_id = $newChapter->id;
                    $newObjective->course_id = $newCourse->id;
                    $newObjective->created_at = Carbon::now();
                    $newObjective->push();
                }

                foreach($lesson->lesson_tasks as $task) {
                    $newTask = $task->replicate();
                    $newTask->lesson_id = $newLesson->id;
                    $newTask->chapter_id = $newChapter->id;
                    $newTask->course_id = $newCourse->id;
                    $newTask->created_at = Carbon::now();
                    $newTask->push();
                }

            }
        }

        foreach($course->tags as $tag) {
            DB::table('course_course_tag')->insert([
                'course_id' => $newCourse->id,
                'course_tag_id' => $tag->id
            ]);
        }

        foreach($course->jobRoles as $role) {
            DB::table('job_rollables')->insert([
                'job_rollable_id' => $newCourse->id,
                'job_role_id' => $role->id,
                'job_rollable_type' => Course::class
            ]);
        }

        foreach($course->instructors as $instuctor) {
            if(!$instuctor) return;
            DB::table('course_user')->insert([
                'course_id' => $newCourse->id,
                'user_id' => $instuctor->id
            ]);
        }

        foreach($course->specialtyAreas as $area) {
            DB::table('specialty_areables')->insert([
                'specialty_areable_id' => $newCourse->id,
                'specialty_area_id' => $area->id,
                'specialty_areable_type' => Course::class
            ]);
        }

        foreach($course->packages as $package) {
            $newPackage = $package->replicate();
            $newPackage->course_id = $newCourse->id;
            $newPackage->created_at = Carbon::now();
            $newPackage->push();
        }

        foreach($course->assessments as $assessment) {
            $newAssessment = $assessment->replicate();
            $newAssessment->course_id = $newCourse->id;
            $newAssessment->created_at = Carbon::now();
            $newAssessment->related_lesson_id = null;
            $newAssessment->push();

            if($assessment->correct_answer) {
                $newCorrectAnswer = $assessment->correct_answer->replicate();
                $newCorrectAnswer->course_assessments_id = $newAssessment->id;
                $newCorrectAnswer->push();
            }

            foreach($assessment->answers as $anser) {
                $newAnswer = $anser->replicate();
                $newAnswer->assessment_id = $newAssessment->id;
                $newAnswer->created_at = Carbon::now();
                $newAnswer->push();
            }

        }

        foreach($course->course_learns as $learn) {
            $newLearn = $learn->replicate();
            $newLearn->course_id = $newCourse->id;
            $newLearn->created_at = Carbon::now();
            $newLearn->push();
        }

        return back()->with('success', "Course Duplicated Successfully with name {$newCourse->name}");


    }

}
