<?php

namespace App\Domains\Enterprise\Repositories;

use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use Carbon\Carbon;
use DateTime;

class EnterpriseLearnPathRepository extends Repository
{
    public function __construct(EnterpriseLearnPath $model)
    {
        parent::__construct($model);
    }

    public function getLearnPathsForEnterprise($enterprise_id)
    {
        $query = $this->getModel()->newQuery();
        $query->active()
            ->where('enterprise_id', $enterprise_id);
        return $query;

    }


    public function getLearnPathsForEnterpriseWithFilteration($enterprise_id, $request)
    {
        //TODO update code as learnpaths is only many to many relation not a model any more
//        dd($request->date);
        $perPage = $request->perPage != null ? $request->perPage : 10;
        $request->order_by = $request->order_by != null ? $request->order_by : 'created_at';
        $request->order_direction = $request->order_direction != null ? $request->order_direction : 'desc';
        $query = $this->getModel()->newQuery();
        $package_ids = $query->active()
            ->where('enterprise_id', $enterprise_id)->pluck('package_id');
        $query = PackageSubscription::whereIn('id', $package_ids)->where('activation',true);
        $query->where('name', 'like', "%{$request->search}%");
        $query->when($request->start_date !== null, function ($q) use ($request) {
            $q->where('created_at', '>', $request->start_date);
        });
        // $query->when(!$request->start_date , function ($q) use ($request) {
        //     $q->where('created_at', '>', now()->subDays(30));
        // });
        $query->when($request->end_date !== null, function ($q) use ($request) {
            $end_date =new DateTime( $request->end_date);
            $end_date= $end_date->modify('+1 day');
            return $q->whereDate('created_at', "<=",$end_date );
        });

        $query->when($request->status !== null, function ($q) use ($request) {
            $q->where('activation', '=', $request->status);
        });

        $query->when($request->deadline_type !== null, function ($q) use ($request) {
            $q->where('deadline_type', '=', $request->deadline_type);
        });
        $query->orderBy($request->order_by, $request->order_direction);
//            return $query
//        $query= $query->when($request->search, function ($query) use ($request) {
//            return $query->where(function ($query) use ($request) {
//                return $query->where('packge.name', "LIKE", "%{$request->search}%");
//            });
//        });
        return $query->paginate($perPage);
    }

    public function getPurchasedLearnPathsWhereNotFinishedOrEnrolled(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $learn_paths_ids = $this->getModel()->whereIn('package_id', $subscription_ids)
            ->where('enterprise_id', $user->enterprise_id)
            ->pluck('id')->toArray();


        $purchased_learn_paths = $this->getModel()->whereIn('id', $learn_paths_ids)
            ->whereHas('package', function ($query) use ($user){
                $query->whereHas('courses', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->whereHas('cousre_enrollments', function ($query) use ($user) {
                            $query->where('user_id', '!=', $user->id);
                        })
                            ->orWhereDoesntHave('cousre_enrollments');
                    });

                });
            })
            ->whereNotNull('enterprise_id')
            ->where('enterprise_id', $user->enterprise_id)
            ->get();
        return $purchased_learn_paths;
    }

    public function getPurchasedEnterPriseLearnPathsWhereInProgress(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $learn_paths_ids = $this->getModel()->whereIn('package_id', $subscription_ids)
            ->where('enterprise_id', $user->enterprise_id)
            ->pluck('id')->toArray();

        $purchased_learn_paths = $this->getModel()->whereIn('id', $learn_paths_ids)
            ->whereHas('package', function ($query) use ($user){
                $query->whereHas('courses', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->whereHas('cousre_enrollments', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        });
                    });
                });
            })
            ->where('enterprise_id', $user->enterprise_id)
            ->get();

        return $purchased_learn_paths;
    }

    /**
     * Check for enterprise subscribed packages and return true if it has a pro package
     * @param array $ids
     *
     * @return bool
     */
    public function checkForProPackage(array $ids): bool
    {
        $packages = $this->getModel()
            ->newQuery()
            ->active()
            ->whereIn('id', $ids)
            ->with(['package'])
            ->get()
            ->pluck('package.access_type')
            ->toArray();
        return in_array(AccessType::PRO, $packages);
    }

}
