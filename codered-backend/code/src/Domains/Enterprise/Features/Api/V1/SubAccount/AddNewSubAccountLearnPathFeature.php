<?php

namespace App\Domains\Enterprise\Features\Api\V1\SubAccount;

use App\Domains\Enterprise\Http\Requests\Api\V1\SubAccount\AddLearnPathToSubAccountRequest;
use App\Domains\Enterprise\Http\Resources\Api\V1\SubAccount\SubAccountDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\SubAccount\AssignLearnPathToSubAccountJob;
use App\Domains\User\Models\User;
use App\Foundation\Traits\Authenticated;
use Illuminate\Http\Resources\Json\JsonResource;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class AddNewSubAccountLearnPathFeature extends Feature
{
    use Authenticated;

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(AddLearnPathToSubAccountRequest $request)
    {
        $admin = auth()->user();

        $user = User::where('id', $request->user_id)
            ->where(function ($query) use ($admin) {
                $query->where('enterprise_id', $admin->id)
                    ->orwhere('subaccount_id', $admin->id);
            })->first();

        if (!$user) {
            $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    'name' => 'user',
                    'message' => 'No User Found'
                ]
            ]);
        }
        $this->run(AssignLearnPathToSubAccountJob::class, [
            "packages" => $request->learn_paths,
            "subAccount_id" => $request->user_id
        ]);

        $updateUser = User::where('id',$request->user_id)->first();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'user' => new SubAccountDetailsResource($updateUser)
            ]
        ]);

    }
}
