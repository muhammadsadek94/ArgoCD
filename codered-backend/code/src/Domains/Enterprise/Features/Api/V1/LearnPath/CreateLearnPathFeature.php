<?php

namespace App\Domains\Enterprise\Features\Api\V1\LearnPath;

use App\Domains\Enterprise\Http\Requests\Api\V1\LearnPath\CreateLearnPathRequest;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathDetailsCollection;
use App\Domains\Enterprise\Jobs\Api\V1\LearnPath\CreateLearnPathJob;
use App\Domains\Enterprise\Jobs\Api\V1\LearnPath\CreatePackageJob;
use App\Foundation\Traits\Authenticated;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class CreateLearnPathFeature extends Feature
{
    use Authenticated;

    public function handle(CreateLearnPathRequest $request)
    {

        $admin = $this->auth('api');
        $package = $this->run(CreatePackageJob::class, [
                'request' => $request,
                'admin'   => $admin
            ]

        );

        $learnPaths = $this->run(CreateLearnPathJob::class, [
            'admin' => $admin,
            'package' => $package
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'learnPath' => $learnPaths
            ]
        ]);
    }
}
