<?php

namespace App\Domains\Course\Listeners\Course\ActiveCampaign;

use App\Domains\Course\Events\Course\LessonCompleted;
use App\Domains\Course\Events\Course\LessonCompletedInLearnPath;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonCompletedInLearnPathEventToActiveCampaign implements ShouldQueue
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
    public function handle(LessonCompletedInLearnPath $event)
    {
        $lesson = $event->lesson;
        $user = $event->user;
        $learn_path = $event->learn_path;
        $response = $this->ac->lessonCompletedInLearnPath($user, $lesson, $learn_path);

    }
}
