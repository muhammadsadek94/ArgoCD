<?php

namespace App\Domains\Enterprise\Features\Api\V1\SubAccount;

use App\Domains\Enterprise\Http\Requests\Api\V1\SubAccount\UpdateSubAccountProfileRequest;
use App\Domains\Enterprise\Http\Requests\Api\V1\User\UpdateProfileRequest;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\UpdateProfileJob;
use App\Domains\User\Models\User;
use App\Foundation\Traits\Authenticated;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class UpdateSubAccountProfileFeature extends Feature
{
    use Authenticated;

    public function handle(UpdateSubAccountProfileRequest $request)
    {
        $admin = $this->auth('api');
        $user = User::where('id', $request->subAccount)
            ->where(function ($query) use ($admin) {
                $query->where('enterprise_id', $admin->id)
                    ->orWhere('subaccount_id', $admin->id);
            })->firstOrFail();
        $user = $this->run(UpdateProfileJob::class, [
            'request' => $request,
            'user' => $user
        ]);


        $user = $user->fresh();
        return $this->run(RespondWithJsonJob::class, [
            "content" => new EnterpriseUserDetailsResource($user)
        ]);
    }
}
