<?php

namespace App\Domains\Course\Features\Api\V1\User;

use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Payments\Http\Resources\Api\V1\User\PackageResource;
use App\Domains\Payments\Repositories\PackageSubscriptionRepositoryInterface;



class GetProPageFeature extends Feature
{


    public function handle(CourseRepositoryInterface $course_repository,PackageSubscriptionRepositoryInterface $package_subscription_repository)
    {
        $featured_courses = $course_repository->getFeaturedCourses();

        //Fetching Pro Packages//
        $pro_packages = $package_subscription_repository->getProSubscriptions();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'featured_courses' => CourseBasicInfoResource::collection($featured_courses),
                'pro_packages' => PackageResource::collection($pro_packages),

            ]
        ]);
    }
}
