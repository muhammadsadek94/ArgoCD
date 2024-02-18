<?php

namespace App\Domains\Course\Features\Api\V1\User;

use App\Domains\Bundles\Http\Resources\BundlesBasicInformationResource;
use App\Domains\Bundles\Http\Resources\PromoCodeInformationResource;
use App\Domains\Bundles\Repositories\Interfaces\BundlesRepositoryInterface;
use App\Domains\Bundles\Repositories\Interfaces\PromoCodeRepositoryInterface;
use App\Domains\Course\Http\Resources\Api\V1\CourseBasicInfoResource;
use App\Domains\Course\Repositories\CourseRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class GetFeaturedCourseFeature extends Feature
{

    public function handle(CourseRepositoryInterface $course_repository, PromoCodeRepositoryInterface $promocode_repository,
        BundlesRepositoryInterface $bundles_repository)
    {
        $featured_courses = $course_repository->getFeaturedCourses(30, ['image', 'category']);
        $micro_degrees = $course_repository->getMicrodegrees(30, ['image', 'category']);
//
//        //This API is updated for adding new homepage details//
        $promo_code = $promocode_repository->getPromoCodeDetails();
        $bestseller = $bundles_repository->getBestsellerBundles();
        $new_arrival = $bundles_repository->getNewArrivalBundles();
        $spotlight = $bundles_repository->getSpotlightBundles();
        $bundle_of_day = $bundles_repository->getBundleOfDayBundles();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'featured_courses' => CourseBasicInfoResource::collection($featured_courses),
                'micro_degrees'    => CourseBasicInfoResource::collection($micro_degrees),
                'promo_code'       => !empty($promo_code) ? new PromoCodeInformationResource($promo_code) : null,
                'bestseller'       => BundlesBasicInformationResource::collection($bestseller),
                'new_arrival'      => BundlesBasicInformationResource::collection($new_arrival),
                'spotlight'        => BundlesBasicInformationResource::collection($spotlight),
                'bundle_of_day'    => !empty($bundle_of_day) ? new BundlesBasicInformationResource($bundle_of_day) : null,

            ]
        ]);
    }
}
