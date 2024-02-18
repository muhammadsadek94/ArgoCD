<?php

namespace App\Domains\Enterprise\Http\Controllers\Api\V1\User;

use App\Domains\Enterprise\Features\Api\V1\DeactivateUsersFeature;
use App\Domains\Enterprise\Features\Api\V1\User\AddLearnPathsToUserFeature;
use App\Domains\Enterprise\Features\Api\V1\User\DeleteSubscriptionFeature;
use App\Domains\Enterprise\Features\Api\V1\User\GetUsersByIdFeature;
use App\Domains\Enterprise\Features\Api\V1\User\GetUserTags;
use App\Domains\Enterprise\Features\Api\V1\User\UpdateUserFeature;
use INTCore\OneARTFoundation\Http\Controller;
use App\Domains\Enterprise\Features\Api\V1\User\GetUsersFeature;

class GetUserController extends Controller
{
    /**
     * Get Users
     */
    public function index()
    {
        return $this->serve(GetUsersFeature::class);
    }


    public function show()
    {
        return $this->serve(GetUsersByIdFeature::class);

    }
    public function update()
    {
        return $this->serve(UpdateUserFeature::class);
    }
    public function deleteAll()
    {
        return $this->serve(DeactivateUsersFeature::class);
    }

    public function addNewLearnPath()
    {
        return $this->serve(AddLearnPathsToUserFeature::class);
    }


    public function getTags()
    {
        return $this->serve(GetUserTags::class);
    }

    public function deleteUserLearnPath()
    {
//        return $this->serve(DeleteSubscriptionFeature::class);
    }

}
