<?php

namespace App\Domains\Bundles\Features\Api\V1\User;

use App\Domains\Bundles\Http\Resources\BundlesInformationResource;
use App\Domains\Bundles\Http\Resources\PromoCodeInformationResource;
use App\Domains\Bundles\Repositories\Interfaces\BundlesRepositoryInterface;
use App\Domains\Bundles\Repositories\Interfaces\PromoCodeRepositoryInterface;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class GetBundleByIdFeature extends Feature
{

    public function handle(Request $request, BundlesRepositoryInterface $bundles_repository, PromoCodeRepositoryInterface $promocode_repository)
    {


        $bundle = $bundles_repository->getBundleById($request->bundle_id);
        $promo_code = $promocode_repository->getPromoCodeDetails();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'bundle'     => !empty($bundle) ? new BundlesInformationResource($bundle) : null,
                'promo_code' => !empty($promo_code) ? new PromoCodeInformationResource($promo_code) : null,

            ]
        ]);
    }
}
