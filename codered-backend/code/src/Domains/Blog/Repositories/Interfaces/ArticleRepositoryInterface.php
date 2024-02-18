<?php

namespace App\Domains\Blog\Repositories\Interfaces;

use App\Domains\Blog\Models\Article;
use App\Foundation\Repositories\RepositoryInterface;

interface ArticleRepositoryInterface extends RepositoryInterface
{

    /**
     * get related article depend on current article
     * related article is the latest article in the same article category
     *
     * @param int     $limit
     * @param Article $article
     * @return mixed
     */
    public function getRelatedArticle(Article $article, int $limit = 5);


    /**
     * get recommended article depend on most viewed articles that's created in current month
     *
     * @param int     $limit
     * @param Article $article
     * @return mixed
     */
    public function getRecommendedArticle(int $limit = 5);



    /**
     * get featured article that's marked as featured from admin panel
     *
     * @param int     $limit
     * @return mixed
     */
    public function getFeaturedArticles(int $limit = 5);

    /**
     * get popular article which has highest views
     *
     * @param int     $limit
     * @return mixed
     */
    public function getPopularArticle(int $limit = 5);

    /**
     * get article by id then increment the views by 1
     *
     * @param string $article_id
     * @return mixed
     */
    public function getArticleById(string $article_id);

    /**
     * get latest news
     *
     * @param int $limit
     * @return mixed
     */
    public function getLatestArticles(int $limit = 6);

    /**
     * like / dislike
     * if user not like the article, so add like for article in case article is already liked so remove like
     *
     * @param string $article_id
     * @param string $user_id
     * @return mixed
     */
    public function interactWithArticle(string $article_id, string $user_id);




}
