<?php

namespace App\Domains\Payments\Repositories;

use App\Domains\User\Models\User;
use App\Domains\Payments\Models\PaymentTransactionsHistory;
use App\Domains\Payments\Models\PaymentTransactions;
use App\Foundation\Repositories\Repository;
use App\Foundation\Repositories\RepositoryInterface;

class PaymentTransactionsRepository extends Repository implements RepositoryInterface
{
    public function __construct(PaymentTransactions $model)
    {
        parent::__construct($model);
    }


    /**
     * Get payments and transaction history
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactionsHistory(User $user)
    {
        return $user->transactions()->latest()->get();
    }

}
