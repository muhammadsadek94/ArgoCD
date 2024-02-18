<?php

namespace App\Domains\Course\Listeners\Course\ActiveCampaign;

use App\Domains\Course\Events\Course\CourseCompleted;
use App\Domains\Course\Events\Course\CourseCompletedInLearnPath;
use App\Domains\Course\Events\Learnpath\CheckCompletedCoursesEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CourseCompletedInLearnPathEventToActiveCampaign implements ShouldQueue
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
    public function handle(CourseCompletedInLearnPath $event)
    {
        $user = $event->user;
        $course = $event->course;
        $learn_path = $event->learn_path;
        event(new CheckCompletedCoursesEvent($user));

        $response = $this->ac->courseCompletedInsideLearnPath($user, $course, $learn_path);

    }
}
