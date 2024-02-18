<?php

namespace App\Domains\Course\Listeners\Learnpath;

use App\Domains\Course\Events\Learnpath\CheckCompletedCoursesEvent;
use App\Domains\Course\Jobs\Api\V1\User\GenerateCertificateJob;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\LearnPathCertificate;
use App\Domains\Payments\Repositories\LearnPathInfoRepositoryInterface;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use DB;
use Log;

class CreateCertificatesForCompletedLearnPathsListener implements ShouldQueue
{
    use InteractsWithQueue;

    private $packageSubscriptionRepository;
    private $learnPathInfoRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct( PackageSubscriptionRepositoryInterface $packageSubscriptionRepository, LearnPathInfoRepositoryInterface $learnPathInfoRepository)
    {
        $this->packageSubscriptionRepository = $packageSubscriptionRepository;
        $this->learnPathInfoRepository = $learnPathInfoRepository;
    }


    /**
     * Handle the event.
     *
     * @param CheckCompletedCoursesEvent $event
     * @return void
     */
    public function handle(CheckCompletedCoursesEvent $event)
    {

        /**
         * $user = User::find(1);
         * dispatch(new CheckCompletedCoursesEvent($user));
         **/
        $learnPaths = $this->packageSubscriptionRepository->getPurchasedLearnPaths($event->user); // add where condition to get user's learn paths only getActiveSubscription(AccessType::pro)\

        foreach ($learnPaths as $learnPath) {
            $courses = $learnPath->courses;
            $completedCourses = $event->user->completed_courses()->whereIn('course_id', $courses->pluck('course_id')->toArray())->count();

            if ($completedCourses == $courses->count()) {
                Log::info($learnPath . '========= leanr path');
                // create certificate
                $this->learnPathInfoRepository->createCertificateFile($event->user->id, $learnPath->id, 0);
            }
        }

    }
}

