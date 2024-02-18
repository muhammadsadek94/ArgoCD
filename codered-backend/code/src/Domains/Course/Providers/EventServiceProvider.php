<?php

namespace App\Domains\Course\Providers;

use App\Domains\Course\Events\Course\AssessmentFailed;
use App\Domains\Course\Events\Course\AssessmentPassed;
use App\Domains\Course\Events\Course\CertificateGenerated;
use App\Domains\Course\Events\Course\ChapterCompleted;
use App\Domains\Course\Events\Course\CourseCompleted;
use App\Domains\Course\Events\Course\CourseCompletedInLearnPath;
use App\Domains\Course\Events\Course\CourseEnrollment;
use App\Domains\Course\Events\Course\CourseEnrollmentInLearnPath;
use App\Domains\Course\Events\Course\FirstChapterCompleted;
use App\Domains\Course\Events\Course\GenerateCertificate;
use App\Domains\Course\Events\Course\LessonCompleted;
use App\Domains\Course\Events\Course\LessonCompletedInLearnPath;
use App\Domains\Course\Events\Learnpath\CheckCompletedCoursesEvent;
use App\Domains\Course\Listeners\Course\ActiveCampaign\AssessmentFailedEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\AssessmentPassedEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\CertificateGeneratedEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\ChapterCompletedEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\CourseCompletedEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\CourseCompletedInLearnPathEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\CourseEnrollmentEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\CourseEnrollmentInLearnPathEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\FirstChapterCompletedEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\LessonCompletedEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\ActiveCampaign\LessonCompletedInLearnPathEventToActiveCampaign;
use App\Domains\Course\Listeners\Course\Logic\CreateCertificate;
use App\Domains\Course\Listeners\Learnpath\CreateCertificatesForCompletedLearnPathsListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        LessonCompleted::class  => [
            LessonCompletedEventToActiveCampaign::class
        ],
        LessonCompletedInLearnPath::class  => [
            LessonCompletedInLearnPathEventToActiveCampaign::class
        ],
        CourseEnrollment::class => [
            CourseEnrollmentEventToActiveCampaign::class
        ],
        CourseEnrollmentInLearnPath::class => [
            CourseEnrollmentInLearnPathEventToActiveCampaign::class
        ],
        AssessmentPassed::class => [
            AssessmentPassedEventToActiveCampaign::class
        ],
        AssessmentFailed::class => [
            AssessmentFailedEventToActiveCampaign::class
        ],
        CourseCompleted::class => [
            CourseCompletedEventToActiveCampaign::class,
        ],
        GenerateCertificate::class => [
            CreateCertificate::class
        ],
        ChapterCompleted::class => [
            ChapterCompletedEventToActiveCampaign::class
        ],
        FirstChapterCompleted::class => [
            FirstChapterCompletedEventToActiveCampaign::class
        ],
        CheckCompletedCoursesEvent::class => [
            CreateCertificatesForCompletedLearnPathsListener::class
        ],
        CourseCompletedInLearnPath::class => [
            CourseCompletedInLearnPathEventToActiveCampaign::class
        ],
        CertificateGenerated::class => [
            CertificateGeneratedEventToActiveCampaign::class
        ]


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
