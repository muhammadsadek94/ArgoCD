<?php

namespace App\Domains\Blog\Features\Api\V1\User;

use INTCore\OneARTFoundation\Feature;
use App\Domains\Blog\Http\Requests\Api\SaveArticleLikesRequest;
use App\Domains\Blog\Jobs\Api\V1\User\SaveArticleLikesJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Blog\Models\ArticleLikes;
use App\Domains\User\Models\User;
use App\Domains\Blog\Models\Article;

class SaveArticleLikesFeature extends Feature
{

    public function handle(SaveArticleLikesRequest $request)
    {


        $user = $request->user('api');

        //If a particular user has already liked this article then show error msg//
       /* if ($user->article_likes()->where('article_id', $request->article_id)->count() > 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name"    => "message",
                    'message' => trans('You have already liked this article'),
                ]
            ]);
        }*/

        $this->run(SaveArticleLikesJob::class, [
            'user'           => $user,
            'article_id'     => $request->article_id,
        ]);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'message' => 'success'
            ]
        ]);
    }
}
