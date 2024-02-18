<?php


namespace App\Domains\Enterprise\Repositories;


use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Models\License;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use App\Foundation\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

class EnterpriseRepository extends Repository implements RepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getLicenses($request, $admin)
    {
        $license_type = LicneseType::PREMIUM;
        if (isset($request->license_type)) {
            $license_type = $request->license_type;
        }
        if ($admin->type == UserType::PRO_ENTERPRISE_ADMIN || $admin->type == UserType::REGULAR_ENTERPRISE_ADMIN) {
            $licences = $admin->licenses()->where('user_id', null)->where('subaccount_id', null)->where('activation', '=', 1)
                ->where('used_number', '<', $admin->enterpriseInfo ? $admin->enterpriseInfo->licenses_reuse_number : 1)
                ->where('license_type', $license_type)->orderByDesc('used_number')->get();

        } else {

            $licences = $admin->subLicenses()->where('user_id', null)->where('activation', '=', 1)
                ->where('license_type', $license_type)
                ->where('used_number', '<', $admin->enterpriseAccount->enterpriseInfo ? $admin->enterpriseAccount->enterpriseInfo->licenses_reuse_number : 1)->get();
        }
        return $licences;
    }

    public function assignLicensesToUser($user, $license)
    {
        $license->user_id = $user->id;
        $license->status = LicneseStatusType::USED;
        $license->used_number = (int)$license->used_number + 1;
        $license->save();
    }


    function getEnterpriseSubAccount($admin)
    {
        $query = $this->getModel()->newQuery();
        //        $query = $query->where('activation','>', 0);
        $query = $query->where(function ($query) use ($admin) {
            $query->where('enterprise_id', $admin->id)
                ->orWhere('subaccount_id', $admin->id);
        });
     return   $query->whereIn('type', [UserType::REGULAR_ENTERPRISE_SUBACCOUNT, UserType::PRO_ENTERPRISE_SUBACCOUNT]);
    }

    public function removeAssignLicensesToUser($user)
    {
        $license = License::where('user_id', $user->id)->first();
        if ($license) {
            $license->user_id = null;
            $license->save();
        }
    }
}
