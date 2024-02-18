<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserCollection;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\GetUsersJob;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserDetailsResource;

class GetUsersFeature extends Feature
{
    public function handle(Request $request, EnterpriseRepository $enterpriseRepository)
    {
        $user = $this->run(GetUsersJob::class, [
            'request' => $request
        ]);
        $admin = $request->user('api');
        $licenses = $enterpriseRepository->getLicenses($request, auth()->user())->count();
        $subAccounts = $enterpriseRepository->getEnterpriseSubAccount($admin)->get();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'users' => new EnterpriseUserCollection($user),
                    'licenses' => $licenses,
                    'subAccounts' =>$subAccounts

            ]
        ]);
    }
}
