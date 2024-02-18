<?php

namespace App\Domains\Course\Listeners\Course\ActiveCampaign;

use App\Domains\Course\Events\Course\AssessmentFailed;
use App\Domains\Course\Events\Course\LessonCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssessmentFailedEventToActiveCampaign implements ShouldQueue
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
     * @param LessonCompleted $event
     * @return void
     */
    public function handle(AssessmentFailed $event)
    {
        $course = $event->course;
        $user = $event->user;
        $response = $this->ac->failedAssessmentFailed($user, $course);

    }
}
