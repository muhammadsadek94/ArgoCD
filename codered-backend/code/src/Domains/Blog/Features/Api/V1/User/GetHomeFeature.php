<?php

namespace App\Domains\Blog\Features\Api\V1\User;

use App\Domains\Blog\Http\Resources\ArticleBasicInfoResource;
use App\Domains\Blog\Http\Resources\ArticleResource;
use App\Domains\Blog\Http\Resources\QuoteResource;
use App\Domains\Blog\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Domains\Blog\Repositories\Interfaces\QuotesRepositoryInterface;
use INTCore\OneARTFoundation\Feature;
use Illuminate\Http\Request;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;


class GetHomeFeature extends Feature
{

    public function handle(Request $request, ArticleRepositoryInterface $article_repository,QuotesRepositoryInterface $quote_repository)
    {

        $featured = $article_repository->getFeaturedArticles();
        $popular = $article_repository->getPopularArticle();
        $latest = $article_repository->getLatestArticles();
        $quotes = $quote_repository->getAllQuotes();

        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'featured' => ArticleBasicInfoResource::collection($featured),
                'popular' => ArticleBasicInfoResource::collection($popular),
                'latest' => ArticleBasicInfoResource::collection($latest),
                'quotes' => QuoteResource::collection($quotes),
            ]
        ]);
    }
}
