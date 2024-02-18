<?php

namespace App\Domains\User\Repositories;

use App\Domains\User\Models\Instructor\Payout;
use App\Foundation\Repositories\Repository;
use App\Foundation\Repositories\RepositoryInterface;

class PayoutRepository extends Repository implements RepositoryInterface
{
    public function __construct(Payout $model)
    {
        parent::__construct($model);
    }

    public function getTotalPayouts(string $instructor_id): int
    {
        return $this->getModel()->where('user_id', $instructor_id)->NotRejected()->sum('amount');
    }

    public function getLastQuarterAmount(string $instructor_id): int
    {
        return $this->getModel()->where('user_id', $instructor_id)
            ->paid()
            ->latest()
            ->limit(1)
            ->sum('amount');
    }

    public function getPendingPayouts(string $instructor_id)
    {
        return $this->getModel()->where('user_id', $instructor_id)
            ->pendingAndApproved()->with('course')
            ->latest()
            ->get();
    }

    public function getHistoryPayouts(string $instructor_id)
    {
        return $this->getModel()->where('user_id', $instructor_id)
            ->history()
            ->with('course')
            ->latest()
            ->get();
    }


    public function getNewTotalRoyalties(string $instructor_id): int
    {
        $payouts = $this->getModel()->where('user_id', $instructor_id)
            ->paid()
            ->get();

        $total = 0;
        foreach ($payouts as $payout) {
            $total += (int)$payout->royalty;
        }
        return $total;
    }

    public function getNewLastQuarterAmount(string $instructor_id): int
    {
        $payout =  $this->getModel()->where('user_id', $instructor_id)
            ->paid()
            ->latest()
            ->limit(1)->first();

        if (!$payout) {
            return 0;
        }
        
        $total = (int)$payout->royalty;
        return $total;

    }

}
