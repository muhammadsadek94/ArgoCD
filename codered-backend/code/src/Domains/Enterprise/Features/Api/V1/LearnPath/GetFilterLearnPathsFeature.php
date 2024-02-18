<?php

namespace App\Domains\Enterprise\Features\Api\V1\LearnPath;

use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathDetailsCollection;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathIndexCollection;
use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepository;
use App\Foundation\Traits\Authenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class GetFilterLearnPathsFeature extends Feature
{
    use Authenticated;

    const CACHE_TTL = 86400;

    public function handle( Request $request , EnterpriseLearnPathRepository $enterpriseLearnPathRepository )
    {
        $admin = $this->auth('api');
//        return Cache::remember("enterprise_dashboard_learn-paths_{$admin->id}" , self::CACHE_TTL, function () use ($admin, $enterpriseLearnPathRepository, $request) {
            $learnPaths =  $enterpriseLearnPathRepository->getLearnPathsForEnterpriseWithFilteration( $admin->id , $request );
            $learnPaths->map(function ($learnpath) use ($admin){
                $learnpath->enterpriseId = $admin->id;
                return $learnpath;
            });

            return $this->run(RespondWithJsonJob::class, [
                "content" => [
                    'learnPaths' =>  new EnterpriseLearnPathIndexCollection($learnPaths)
                ]
            ]);
//        });
    }
}
