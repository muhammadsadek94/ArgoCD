<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathsResource;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use Illuminate\Http\Request;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\GetUsersJob;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserDetailsResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserTagsResource;
use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepository;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\Lookups\UserTag;
use App\Domains\User\Models\User;
use App\Foundation\Traits\Authenticated;

class GetEnterpriseDataToCreateUsers extends Feature
{
    use Authenticated;

    public function handle(Request $request, EnterpriseLearnPathRepository $learnPathRepo , EnterpriseRepository $enterpriseRepository)
    {

        $auth = $this->auth('api');
        $learnPath = $learnPathRepo->getLearnPathsForEnterprise($auth->id)->get();
        $user_tags_lists = UserTag::where('type' , 1)->where('enterprise_id',$auth->id)->get();
        // $test = UserTag::where('name','dafaf')->first();
        // dd(count($test->users_count));
        $subAccount = User::active()
            ->where('enterprise_id', $auth->id)
            ->where('type', $auth->type == UserType::PRO_ENTERPRISE_ADMIN ? UserType::PRO_ENTERPRISE_SUBACCOUNT : UserType::REGULAR_ENTERPRISE_SUBACCOUNT)
            ->get();
//            ->where(function ($query) {
//                $query->where('type', UserType::PRO_ENTERPRISE_SUBACCOUNT)
//                    ->orwhere('type', UserType::REGULAR_ENTERPRISE_SUBACCOUNT);
//            })->get();
        $licenses = $enterpriseRepository->getLicenses($request, auth()->user())->count();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'learnPaths' => EnterpriseLearnPathsResource::collection($learnPath),
                'subAccount' => $subAccount,
                'tags' => EnterpriseUserTagsResource::collection($user_tags_lists),
                'licenses' => $licenses

            ]
        ]);
    }
}
