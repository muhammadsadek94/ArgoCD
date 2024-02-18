<?php

namespace App\Domains\Blog\Repositories;

use App\Domains\Blog\Models\Article;
use App\Domains\Blog\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Foundation\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository extends Repository implements ArticleRepositoryInterface
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function getRelatedArticle(Article $article, int $limit = 5)
    {

        $query = $this->getModel()->newQuery();
        $query->where([
            ['article_category_id', '=', $article->article_category_id],
            ['id', '<>', $article->id],
        ])
            ->orderBy('created_at', 'DESC')
            ->active()
            ->limit($limit);

        return $query->get();

    }

    public function getRecommendedArticle(int $limit = 5)
    {

        $query = $this->getModel()->newQuery();
        return $query->where('created_at', '>=', now()->subDays(30))
            ->orderBy('views', 'DESC')
            ->active()
            ->limit($limit)
            ->get();

    }

    public function getFeaturedArticles(int $limit = 5)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('is_featured', 1)
            ->latest('created_at')
            ->active()
            ->limit($limit)
            ->get();

    }

    public function getPopularArticle(int $limit = 5)
    {
        $query = $this->getModel()->newQuery();
        return $query->where('created_at', '>=', now()->subDays(30))
            ->orderBy('views', 'DESC')
            ->active()
            ->limit($limit)->get();
    }

    public function getArticleById(string $article_id)
    {
        $article = $this->getModel()->active()
            ->find($article_id);

        if (!empty($article)) {
            $article->update([
                'views' => (int) ($article->views) + 1,
            ]);
        }

        return $article;


    }

    public function getLatestArticles(int $limit = 6)
    {
        $query = $this->getModel()->newQuery();
        return $query->latest('created_at')
            ->active()
            ->paginate($limit);

    }

    public function interactWithArticle(string $article_id, string $user_id)
    {
        // TODO: Implement interactWithArticle() method.
    }
}
