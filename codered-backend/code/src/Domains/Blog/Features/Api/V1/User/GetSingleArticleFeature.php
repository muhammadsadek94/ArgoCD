<?php

namespace App\Domains\Blog\Features\Api\V1\User;

use App\Domains\Blog\Http\Resources\ArticleBasicInfoResource;
use App\Domains\Blog\Http\Resources\ArticleResource;
use App\Domains\Blog\Http\Resources\QuoteResource;
use App\Domains\Blog\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Domains\Blog\Repositories\Interfaces\QuotesRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Blog\Models\Article;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;

class GetSingleArticleFeature extends Feature
{

    public function handle(Request $request, ArticleRepositoryInterface $article_repository,QuotesRepositoryInterface $quote_repository)
    {

        $article = $article_repository->getArticleById($request->article_id);
        $related = $article_repository->getRelatedArticle($article);
        $quote = $quote_repository->getRandomQuote();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'article' => new ArticleResource($article),
                'related' => ArticleBasicInfoResource::collection($related),
                'quote' => QuoteResource::collection($quote),
            ]
        ]);

    }
}
