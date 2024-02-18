<?php

namespace App\Domains\Favourite\Features\Api\V2\User;

use App\Foundation\Traits\Authenticated;
use App\Domains\Favourite\Http\Requests\Api\V2\User\FavouriteRequest;
use App\Domains\Favourite\Jobs\Api\V2\User\RemoveFavouriteJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;

class DeleteFavouriteFeature extends Feature
{
    use Authenticated;

    public function handle(FavouriteRequest $request)
    {
        $this->run(RemoveFavouriteJob::class, [
            'type'=> $request->type,
            'id' => $request->id,
            'user_id' => $this->auth('api')->id
        ]);

        return $this->run(RespondWithJsonJob::class, [
            'content' => [
                'message' => 'success'
            ]
        ]);
    }
}
