<?php

namespace App\Domains\Bundles\Features\Api\V1\User;

use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Bundles\Repositories\Interfaces\BundlesRepositoryInterface;
use App\Domains\Bundles\Repositories\Interfaces\PromoCodeRepositoryInterface;
use App\Domains\Bundles\Http\Resources\BundlesBasicInformationResource;
use App\Domains\Bundles\Http\Resources\PromoCodeInformationResource;



class GetHomeBundleFeature extends Feature
{

    public function handle(Request $request, BundlesRepositoryInterface $bundles_repository,PromoCodeRepositoryInterface $promocode_repository)
    {


        $featured       = $bundles_repository->getFeaturedBundles();
        $job_based      = $bundles_repository->getJobBasedBundles();
        $skill_based    = $bundles_repository->getSkillBasedBundles();
        $category_based = $bundles_repository->getCategoryBasedBundles();
        $bundle_of_day  = $bundles_repository->getBundleOfDayBundles();
        $bestseller     = $bundles_repository->getBestsellerBundles();
        $new_arrival    = $bundles_repository->getNewArrivalBundles();
        $spotlight      = $bundles_repository->getSpotlightBundles();
        $promo_code     = $promocode_repository->getPromoCodeDetails();



        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'featured' => BundlesBasicInformationResource::collection($featured),
                'job_based' => BundlesBasicInformationResource::collection($job_based),
                'skill_based' => BundlesBasicInformationResource::collection($skill_based),
                'category_based' => BundlesBasicInformationResource::collection($category_based),
                'bundle_of_day' => !empty($bundle_of_day) ? new BundlesBasicInformationResource($bundle_of_day) : null,
                'promo_code'  => !empty($promo_code) ? new PromoCodeInformationResource($promo_code) : null,
                'bestseller' => BundlesBasicInformationResource::collection($bestseller),
                'new_arrival' => BundlesBasicInformationResource::collection($new_arrival),
                'spotlight' => BundlesBasicInformationResource::collection($spotlight),


            ]
        ]);
    }
}
