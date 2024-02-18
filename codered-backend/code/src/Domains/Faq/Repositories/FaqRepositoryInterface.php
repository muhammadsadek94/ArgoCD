<?php

namespace App\Domains\Faq\Repositories;

use App\Domains\Faq\Enum\AppTypes;
use App\Foundation\Repositories\RepositoryInterface;

interface FaqRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $app_type
     * @return mixed
     */
    public function getFaqData($app_type = AppTypes::USER_APP);
}