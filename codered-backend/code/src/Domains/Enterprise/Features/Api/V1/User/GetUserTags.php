<?php

namespace App\Domains\Enterprise\Features\Api\V1\User;

use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathsResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseUserTagsResource;
use INTCore\OneARTFoundation\Feature;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepository;
use App\Domains\User\Models\Lookups\UserTag;
use App\Foundation\Traits\Authenticated;

class GetUserTags extends Feature
{
    use Authenticated;

    public function handle(EnterpriseLearnPathRepository $learnPathRepo)
    {
        $auth = request()->user('api');
        $user_tags_lists = UserTag::where('type', 1)->where('enterprise_id', $auth->id)->get();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'tags' => EnterpriseUserTagsResource::collection($user_tags_lists)
            ]
        ]);
    }
}
