<?php

namespace App\Domains\Enterprise\Repositories;

use App\Domains\User\Models\User;
use App\Foundation\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface EnterpriseLearnPathRepositoryInterface extends RepositoryInterface
{
    public function getLearnPathsForEnterprise($enterprise_id);
    public function getLearnPathsForEnterpriseWithFilteration($enterprise_id, $request);
    public function getPurchasedLearnPathsWhereNotFinishedOrEnrolled(User $user);

}
