<?php

namespace App\Domains\Payments\Repositories;

use App\Domains\User\Models\User;
use App\Foundation\Repositories\RepositoryInterface;

interface PackageSubscriptionRepositoryInterface extends  RepositoryInterface
{
    public function getProSubscriptions();

    public function getCategoriesBundlesSubscriptions();

    public function getUserActiveSubscription(User $user);

    public function getLearnPathsBasedOnCategories($ids);

    public function getPurchasedLearnPaths(User $user);

    public function getPurchasedLearnPathsWhereNotFinishedOrEnrolled(User $user);

    public function getPurchasedLearnPathsWhereFinished(User $user);

    public function getPurchasedLearnPathsWhereInProgress(User $user);

    public function getPurchasedBundles(User $user);

    public function getPurchasedBundlesWhereFinished(User $user);

    public function getPurchasedBundlesWhereNotFinishedOrEnrolled(User $user);

    public function getPurchasedBundlesWhereInProgress(User $user);

}
