<?php

namespace App\Domains\Course\Listeners\Course\ActiveCampaign;

use App\Domains\Course\Events\Course\CourseEnrollment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CourseEnrollmentEventToActiveCampaign implements ShouldQueue
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
    public function handle(CourseEnrollment $event)
    {
        $course = $event->course;
        $user = $event->user;
        $response = $this->ac->enrolledInCourse($user, $course);

    }
}
