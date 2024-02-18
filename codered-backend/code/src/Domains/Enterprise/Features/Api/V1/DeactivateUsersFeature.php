<?php

namespace App\Domains\Enterprise\Features\Api\V1;

use App\Domains\Enterprise\Http\Requests\Api\V1\DeactivateUsersRequest;
use App\Domains\Enterprise\Jobs\Api\V1\DeactivateUsersJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class DeactivateUsersFeature extends Feature
{

    /**
     * Create a new feature instance
     */
    public function __construct()
    {

    }


    public function handle(DeactivateUsersRequest $request)
    {

        $this->run(DeactivateUsersJob::class,[
            'request' => $request
        ]);
        return $this->run(RespondWithJsonJob::class, [
            "content" => 'success'
        ]);
    }
}
