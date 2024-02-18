<?php

namespace App\Domains\Course\Listeners\Course\Logic;

use App\Domains\Course\Events\Course\AssessmentPassed;
use App\Domains\Course\Events\Course\CertificateGenerated;
use App\Domains\Course\Events\Course\GenerateCertificate;
use App\Domains\Course\Repositories\AssessmentsRepositoryInterface;
use App\Domains\User\Events\User\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

/**
 * if course doesnt have assessments.. so create certificate without submit assessment
 *
 * Class CreateCertificate
 *
 * @package App\Domains\Course\Listeners\Course\Logic
 */
class CreateCertificate implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var AssessmentsRepositoryInterface
     */
    private $assessments_repository;

    /**
     * Create the event listener.
     *
     * @param AssessmentsRepositoryInterface $assessments_repository
     */
    public function __construct(AssessmentsRepositoryInterface $assessments_repository)
    {
        $this->assessments_repository = $assessments_repository;
    }

    /**
     * Handle the event.
     *
     * @param GenerateCertificate $event
     * @return void
     */
    public function handle(GenerateCertificate $event)
    {
        $user = $event->user;
        $course = $event->course;

        if ($course->assessments()->count() > 0) return;

        $result = $this->assessments_repository->createCertificateFile($user->id, $course->id, 100);

        event(new AssessmentPassed($course, $user));

        event(new CertificateGenerated($course, $user, $result->certificate->full_url));

        return $result;


    }
}
