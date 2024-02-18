<?php

namespace App\Domains\Blog\Jobs\Api\V1\User;

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use INTCore\OneARTFoundation\Job;
use App\Domains\Blog\Models\Article;
use App\Domains\Blog\Models\ArticleLikes;

class SaveArticleLikesJob extends Job
{

    public  $user;
    private $article_id;


    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $article_id
     */
    public function __construct(User $user, $article_id)
    {
        $this->user = $user;
        $this->article_id = $article_id;
    }

    /**
     * @return ArticleLikes|bool|\Illuminate\Database\Eloquent\Model|mixed|null
     * @throws \Exception
     */
    public function handle()
    {
        $like = ArticleLikes::where([
            'user_id'          => $this->user->id,
            'article_id'       => $this->article_id,
        ])->first();

        if($like) return $like->delete();

        return ArticleLikes::create([
            'user_id'          => $this->user->id,
            'article_id'       => $this->article_id,
        ]);
    }
}
