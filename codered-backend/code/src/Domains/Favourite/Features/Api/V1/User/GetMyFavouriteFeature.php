<?php

namespace App\Domains\Favourite\Features\Api\V1\User;

use App\Foundation\Traits\Authenticated;
use App\Domains\Favourite\Enum\FavouriteType;
use App\Domains\Favourite\Jobs\Api\V1\User\GetUserFavouriteJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Domains\Course\Http\Resources\Api\V1\Lesson\LessonBasicInfoResource;

class GetMyFavouriteFeature extends Feature
{
    use Authenticated;

    public function handle(Request $request)
    {
        $favourites = $this->run(GetUserFavouriteJob::class, [
            'type'=> $request->type,
            'user_id' => $this->auth('api')->id
        ]);

        $data = $favourites;

        if ($request->type == FavouriteType::LESSON) {
            $data = LessonBasicInfoResource::collection($data); //format data via resource
        }


        return $this->run(RespondWithJsonJob::class, [
            'content' => $data
        ]);
    }
}
