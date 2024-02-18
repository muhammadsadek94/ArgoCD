<?php

namespace App\Domains\Enterprise\Features\Api\V1\SubAccount;

use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserCollection;
use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\GetSubAccountsIndexJob;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class GetSubAccountFeature extends Feature
{
    public function handle( Request $request , EnterpriseRepository $enterpriseRepository)
    {
        $user = $this->run(GetSubAccountsIndexJob::class, [
            'request'=> $request
        ]);
        $licenses = $enterpriseRepository->getLicenses($request , auth()->user())->count();
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'users' => new EnterpriseUserCollection($user),
                'licenses' => $licenses
            ]
        ]);
    }
}
