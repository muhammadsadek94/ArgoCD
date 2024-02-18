<?php

namespace App\Domains\Course\Listeners\Course\ActiveCampaign;

use App\Domains\Course\Events\Course\CourseEnrollment;
use App\Domains\Course\Events\Course\CourseEnrollmentInLearnPath;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CourseEnrollmentInLearnPathEventToActiveCampaign implements ShouldQueue
{
    use InteractsWithQueue;

    private $ac;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ac = app('ac');
    }

    /**
     * Handle the event.
     *
     * @param CourseEnrollment $event
     * @return void
     */
    public function handle(CourseEnrollmentInLearnPath $event)
    {
        $course = $event->course;
        $user = $event->user;
        $learn_path = $event->learn_path;
        $response = $this->ac->enrolledInLearnPath($user, $course, $learn_path);

    }
}
