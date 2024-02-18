<?php

namespace App\Domains\Blog\Repositories\Interfaces;

use App\Domains\Blog\Models\Quote;
use App\Foundation\Repositories\RepositoryInterface;

interface QuotesRepositoryInterface extends RepositoryInterface
{

    
 /**
     * get quotes on Home Page
     *
     * @param int     $limit
     * @return mixed
     */
    public function getAllQuotes(int $limit = 5);

  /**
     * get random quotes on Internal Blog Page
     *
     * @return mixed
     */
    public function getRandomQuote();




}
