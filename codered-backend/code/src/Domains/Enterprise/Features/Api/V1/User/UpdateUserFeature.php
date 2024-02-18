<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserDetailsResource;
use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use Illuminate\Http\Resources\Json\JsonResource;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Traits\Authenticated;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\UpdateProfileJob;
use App\Domains\Enterprise\Http\Requests\Api\V1\User\UpdateProfileRequest;

class UpdateUserFeature extends Feature
{
    use Authenticated;

    public function handle(UpdateProfileRequest $request)
    {
        $admin = $this->auth('api');
        $user = User::where('id', $request->user)
            ->where(function ($query) use ($admin) {
                $query->where('enterprise_id', $admin->id)
                    ->orWhere('subaccount_id', $admin->id);
            })->firstOrFail();
        $user = $this->run(UpdateProfileJob::class, [
            'request' => $request,
            'user' => $user
        ]);
        if (!$user) {
            return  $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'message',
                    'message' => 'you don`t have licenses',
                ]
            ]);
        }

        $user = $user->fresh();
        return $this->run(RespondWithJsonJob::class, [
            "content" => new EnterpriseUserDetailsResource($user)
        ]);
    }
}
