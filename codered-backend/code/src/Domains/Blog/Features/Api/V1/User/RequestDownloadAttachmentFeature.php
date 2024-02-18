<?php

namespace App\Domains\Blog\Features\Api\V1\User;

use App\Domains\Blog\Http\Requests\SubmitDownloadFormRequest;
use App\Domains\Blog\Models\Article;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use INTCore\OneARTFoundation\Feature;

class RequestDownloadAttachmentFeature extends Feature
{
    /** @var \App\Domains\User\Services\ActiveCampaign\ActiveCampaignService */
    private $ac;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ac = app('ac');
    }

    public function handle(SubmitDownloadFormRequest $request)
    {

        $article = Article::find($request->article_id);

        $this->ac->submitDownloadArticleDocument($article, $request->email, $request->type);

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'message' => 'success'
            ]
        ]);
    }
}
