<?php

namespace App\Domains\Blog\Repositories;

use App\Domains\Blog\Models\Quote;
use App\Domains\Blog\Repositories\Interfaces\QuotesRepositoryInterface;
use App\Foundation\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class QuotesRepository extends Repository implements QuotesRepositoryInterface
{
    public function __construct(Quote $model)
    {
        parent::__construct($model);
    }


    public function getAllQuotes(int $limit = 3)
    {
        $query = $this->getModel()->newQuery();
        $query->latest('created_at')
              ->active()
              ->limit($limit)
              ->get();

        return $query->get();

    }

    public function getRandomQuote()
    {
        $query = $this->getModel()->newQuery();
        $query->inRandomOrder()
              ->active()
              ->first();

        return $query->get();
    }

}
