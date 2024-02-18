<?php

namespace App\Domains\User\Services\ActiveCampaign;

use App\Domains\Blog\Models\Article;
use App\Domains\ContactUs\Models\ContactUs;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lesson;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\User\Enum\ActiveCampaignEvents;
use App\Domains\User\Models\ActiveCampaignAccount;
use App\Domains\User\Models\User;
use Log;

/*
 * @see ActiveCampaignService
 */
trait EventService
{

    public function lessonCompleted(User $user, Lesson $lesson)
    {

        $completion_rate = round($lesson->course->completedPercentage($user), 2);

        $data = [
            'event'     => ActiveCampaignEvents::LESSON_COMPLETED,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'                    => $lesson->id,
                'lesson_name'           => $lesson->name,
                'completion_rate'       => $completion_rate,
                'chapter_name'          => $lesson->chapter->name,
                'course_name'           => $lesson->course->name,
                'course_sku'            => $lesson->course->sku,
                'course_url'            => env('FRONTEND_URL') . '/course/' . $lesson->course->slug_url,
                'course_sub_category'   => $lesson->course->sub ? $lesson->course->sub->name : null,
                'course_id'             => $lesson->course_id,
                'chapter_id'            => $lesson->chapter_id,
            ])
        ];
        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $lesson->course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    public function lessonCompletedInLearnPath(User $user, Lesson $lesson, LearnPathInfo $learn_path)
    {
        $completion_rate = round($lesson->course->completedPercentage($user), 2);

        $data = [
            'event'     => ActiveCampaignEvents::LESSON_COMPLETED_IN_LEARN_PATH,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'                    => $lesson->id,
                'lesson_name'           => $lesson->name,
                'completion_rate'       => $this->getCompletedPercentageForLearnPath($user, $learn_path),
                'chapter_name'          => $lesson->chapter->name,
                'learning_path_name'    => $learn_path->name,
                'course_sku'            => $lesson->course->sku,
                'course_url'            => env('FRONTEND_URL') . '/course/' . $lesson->course->slug_url,
                'course_name'           => $lesson->course->name,
                'course_id'             => $lesson->course_id,
                'course_sub_category'   => $lesson->course->sub ? $lesson->course->sub->name : null,
                'chapter_id'            => $lesson->chapter_id,
            ])
        ];
        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $lesson->course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    public function enrolledInCourse(User $user, Course $course)
    {
        $data = [
            'event'     => ActiveCampaignEvents::ENROLL_COURSE,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'                    => $course->id,
                'course_name'           => $course->name,
                'course_sku'            => $course->sku,
                'course_url'            => env('FRONTEND_URL') . '/course/' . $course->slug_url,
                'course_sub_category'   => $course->sub->name,
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    public function enrolledInLearnPath(User $user, Course $course, LearnPathInfo $learn_path)
    {
        $data = [
            'event'     => ActiveCampaignEvents::ENROLL_COURSE,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'                    => $course->id,
                'course_name'           => $course->name,
                'completion_rate'       => $this->getCompletedPercentageForLearnPath($user, $learn_path),
                'course_sku'            => $course->sku,
                'course_url'            => env('FRONTEND_URL') . '/course/' . $course->slug_url,
                'course_sub_category'   => $course->sub->name,
                'learning_path_name'    => $learn_path->name,
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    public function courseCompleted(User $user, Course $course)
    {

        $completion_rate = round($course->completedPercentage($user), 2);

        $data = [
            'event'     => ActiveCampaignEvents::COURSE_COMPLETED,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'                => $course->id,
                'course_name'       => $course->name,
                'course_sku'        => $course->sku,
                'course_url'        => env('FRONTEND_URL') . '/course/' . $course->slug_url,
                'completion_rate'   => $completion_rate,
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    public function courseCompletedInsideLearnPath(User $user, Course $course, LearnPathInfo $learn_path)
    {

        $completion_rate = round($course->completedPercentage($user), 2);

        $data = [
            'event'     => ActiveCampaignEvents::COURSE_COMPLETED_IN_LEARNPATH,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'                    => $course->id,
                'course_name'           => $course->name,
                'course_sku'            => $course->sku,
                'course_url'            => env('FRONTEND_URL') . '/course/' . $course->slug_url,
                'learning_path_name'    => $learn_path->name,
                'completion_rate'       => $this->getCompletedPercentageForLearnPath($user, $learn_path),
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            Log::info("course completed inside learn path". json_encode($res));
            $this->updateUserCourseName($user->active_campaign_id, $course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    public function finalAssessmentPassed(User $user, Course $course)
    {
        $data = [
            'event'     => ActiveCampaignEvents::ASSESSMENT_PASSED,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'          => $course->id,
                'course_name' => $course->name,
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    public function failedAssessmentFailed(User $user, Course $course)
    {
        $data = [
            'event'     => ActiveCampaignEvents::ASSESSMENT_FAIL,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'          => $course->id,
                'course_name' => $course->name,
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    /**
     * @param User    $user
     * @param Chapter $chapter
     * @return string
     */
    public function chapterCompleted(User $user, Chapter $chapter)
    {
        $data = [
            'event'     => ActiveCampaignEvents::CHAPTER_COMPLETED,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'           => $chapter->id,
                'chapter_name' => $chapter->name,
                'course_name'  => $chapter->course->name,
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $chapter->course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    /**
     * @param User    $user
     * @param Chapter $chapter
     * @return string
     */
    public function firstChapterCompleted(User $user, Chapter $chapter)
    {
        $data = [
            'event'     => ActiveCampaignEvents::FIRST_CHAPTER_COMPLETED,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'                    => $chapter->id,
                'first_chapter_name'    => $chapter->name,
                'course_name'           => $chapter->course->name,
                'course_objectives'     => $chapter->course_objective
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $chapter->course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    /**
     * @param User    $user
     * @param Chapter $chapter
     * @return string
     */
    public function certificateGenerated(User $user, Course $course, string $url)
    {
        $data = [
            'event'     => ActiveCampaignEvents::CERTIFICATE_GENERATED,
            "email"     => $user->email,
            'eventdata' => json_encode([
                'id'                => $course->id,
                'name'              => $course->name,
                'certificate_url'   => $url,
            ])
        ];

        try {
            $res = $this->ac->api('tracking/log', $data);
            $this->updateUserCourseName($user->active_campaign_id, $chapter->course->name ?? 'Course Name Not Found');
        } catch (\Exception $e) {
            Log::error("failed to update the course name");
        }

        return $res;
    }

    /**
     * @param ContactUs $contact
     * @return string
     */
    public function submitContactUs(ContactUs $contact)
    {
        $isContactExist = ActiveCampaignAccount::where('email', $contact->email)->first();

        if (!$isContactExist) {
            $this->createContact($contact->first_name, $contact->last_name, $contact->email, $contact->phone, 'Codered');
        }

        $data = [
            'event'     => ActiveCampaignEvents::CONTACT_US,
            "email"     => $contact->email,
            'eventdata' => json_encode([
                'id'      => $contact->id,
                'name'    => "{$contact->first_name} {$contact->last_name}",
                'phone'   => $contact->phone,
                'message' => $contact->body,
                'email'   => $contact->email,
            ])
        ];

        return $this->ac->api('tracking/log', $data);
    }

    /**
     * @param Article $article
     * @param         $email
     * @param         $type
     * @return string
     */
    public function submitDownloadArticleDocument(Article $article, $email, $type = 0)
    {
        $isContactExist = ActiveCampaignAccount::where('email', $email)->first();

        if (!$isContactExist) {
            $this->createContact(' ', ' ', $email, ' ', 'Codered');
        }

        $data = [
            'event'     => ActiveCampaignEvents::ARTICLE_DOWNLOAD_ATTACHMENT,
            "email"     => $email,
            'eventdata' => json_encode([
                'id'    => $article->id,
                'name'  => "{$article->name}",
                'email' => $email,
                'type'  => $type
            ])
        ];

        return $this->ac->api('tracking/log', $data);
    }

    /**
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $phone
     * @param string $course_name
     * @return string
     */
    public function requestDownloadSyllabus(string $first_name, string $last_name, string $email, string $phone, string $course_name)
    {
        $isContactExist = ActiveCampaignAccount::where('email', $email)->first();

        if (!$isContactExist) {
            $this->createContact($first_name, $last_name, $email, $phone, 'Codered');
        }

        $data = [
            'event'     => ActiveCampaignEvents::REQUEST_DOWNLOAD_COURSE_SYLLABUS,
            "email"     => $email,
            'eventdata' => json_encode([
                'first_name'  => $first_name,
                'last_name'   => $last_name,
                'email'       => $email,
                'phone'       => $phone,
                'course_name' => $course_name,
            ])
        ];

        return $this->ac->api('tracking/log', $data);
    }

    private function progress(User $user, LearnPathInfo $learn_path)
    {
        $courses_ids = $learn_path->courses->pluck('id');
        $completed_courses_count = CompletedCourses::where('user_id', $user->id)
                    ->whereIn('course_id',$courses_ids)
                    ->groupBy('course_id')
                    ->get()
                    ->count();

        return $completed_courses_count;
    }


    private function getCompletedPercentageForLearnPath(User $user, LearnPathInfo $learn_path)
    {
        $completed_courses = $this->progress($user, $learn_path);

        $completion_percentage = $completed_courses ? ceil(($completed_courses / $learn_path->courses->count()) * 100) : 0;

        return $completion_percentage;
    }

}
