<?php


namespace App\Domains\Enterprise\Services\Users;


use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use App\Domains\User\Models\User;

trait  EnterpiseUserTrait
{
    protected $userEnterpriseRepository;


    public function getUsersQuery($request, $admin_id)
    {
        $e = new UserEnterpriseRepository(User::getModel());
        return $e->getUsersQuery($request, $admin_id);
    }

    public function getSearchWithTImeForTableStatistics($query_enrolment, $request)
    {

        $e = new UserEnterpriseRepository(User::getModel());
        return $e->getSearchWithTImeForTableStatistics($query_enrolment, $request);

    }
}
