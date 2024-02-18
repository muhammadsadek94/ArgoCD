<?php

namespace App\Domains\Bundles\Features\Api\V2\User;

use App\Domains\Bundles\Http\Resources\V2\BundlesFullInformationResource;
use App\Domains\Bundles\Http\Resources\V2\CategoryBundlesResource;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;

class GetBundleByIdFeature extends Feature
{

    public function handle(Request $request)
    {

        $bundle = LearnPathInfo::where('id', $request->bundle_id)
            ->orWhere('slug_url' , $request->bundle_id)
            ->where(function($query){
                $query->where('type', LearnPathType::BUNDLE_CATEGORY)
                    ->orWhere('type', LearnPathType::BUNDLE_COURSES);
            });

        if(!$bundle->exists())
        {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'message' => 'Bundle not exists!',
                ]
            ]);
        }

        if($bundle->first()->type == LearnPathType::BUNDLE_COURSES){
            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'bundle' => new BundlesFullInformationResource($bundle->first()),
                    'packages' => $bundle->first()->pathPackages
                ]
            ]);
        }

        if($bundle->first()->type == LearnPathType::BUNDLE_CATEGORY){
            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'bundle' => new CategoryBundlesResource($bundle->first()),
                    'packages' => $bundle->first()->pathPackages
                ]
            ]);
        }


    }
}

