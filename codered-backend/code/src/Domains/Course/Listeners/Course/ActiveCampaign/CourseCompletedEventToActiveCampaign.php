<?php

namespace App\Domains\Course\Listeners\Course\ActiveCampaign;

use App\Domains\Course\Events\Course\CourseCompleted;
use App\Domains\Course\Events\Learnpath\CheckCompletedCoursesEvent;
use App\Domains\User\Events\User\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CourseCompletedEventToActiveCampaign implements ShouldQueue
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
     * @param CourseCompleted $event
     * @return void
     */
    public function handle(CourseCompleted $event)
    {
        $user = $event->user;
        $course = $event->course;
        event(new CheckCompletedCoursesEvent($user));

        $response = $this->ac->courseCompleted($user, $course);

    }
}
