<?php

namespace App\Domains\Course\Listeners\Course\ActiveCampaign;

use App\Domains\Course\Events\Course\CertificateGenerated;
use App\Domains\Course\Events\Course\ChapterCompleted;
use App\Domains\Course\Events\Course\FirstChapterCompleted;
use App\Domains\User\Events\User\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CertificateGeneratedEventToActiveCampaign implements ShouldQueue
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
     * @param ChapterCompleted $event
     * @return void
     */
    public function handle(CertificateGenerated $event)
    {
        $user = $event->user;
        $course = $event->course;
        $url = $event->url;
        $response = $this->ac->certificateGenerated($user, $course, $url);

    }
}
