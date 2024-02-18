<?php

namespace App\Domains\Payments\Repositories;

use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use Carbon\Carbon;
use Framework\Traits\SelectColumnTrait;

class PackageSubscriptionRepository extends Repository implements PackageSubscriptionRepositoryInterface
{

    use SelectColumnTrait;

    public function __construct(PackageSubscription $model)
    {
        parent::__construct($model);
    }

    public function getProSubscriptions()
    {
        return $this->getModel()->Active()->Pro()->get();
    }

    public function getCategoriesBundlesSubscriptions()
    {
        return $this->getModel()->Active()->CategoriesBundle()->get();
    }

    public function getUserActiveSubscription(User $user)
    {
        $subscription_ids =  $user->active_subscription()->pluck('package_id')->toArray();

        return $this->getModel()->whereIn('id', $subscription_ids)->get();
    }

    public function getLearnPathsBasedOnCategories($ids)
    {
        return $this->getModel()->active()->whereIn('package_category_id', $ids)->get();
    }

    public function getPurchasedLearnPaths(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $learn_paths_ids = $this->getModel()->whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $purchased_learn_paths = LearnPathInfo::whereIn('id', $learn_paths_ids)
            ->select('id', 'name', 'description', 'image_id', 'cover_id', 'slug_url', 'type', 'features', 'activation', 'metadata', 'package_id', 'price')
            ->paths()
            ->with('allCourses:courses.id,timing', 'image:id,path,full_url,mime_type', 'cover:id,path,full_url,mime_type')
            ->with(['completedCourses' => function ($query) use ($user) {
                $query->select(SelectColumnTrait::$completedCoursesColumns)->where('user_id', $user->id);
            }])
            ->with(['user_subscriptions' => function ($query) use ($user) {
                $query->where('user_id', $user->id)->orderBy('expired_at', 'DESC');
            }])
            ->with('package_subscription:id,name,amount,description,activation,access_type,access_id,access_permission,type,duration,deadline_type')
            ->get();

        return $purchased_learn_paths;
    }

    public function getPurchasedLearnPathsWhereNotFinishedOrEnrolled(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $learn_paths_ids = $this->getModel()->whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $purchased_learn_paths = LearnPathInfo::whereIn('id', $learn_paths_ids)
            ->with(['allPathCertificates:id,learnpath_id,user_id'])
            ->when($user, function ($query) use ($user) {
                $query->with(['completedCourses' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }]);
            })->when(!$user, function ($query) {
                $query->with('completedCourses');
            })
            ->whereDoesntHave('allPathCertificates', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('courses', function ($query) use ($user) {
                $query->whereHas('course', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->whereHas('cousre_enrollments', function ($query) use ($user) {
                            $query->where('user_id', '!=', $user->id);
                        })
                            ->orWhereDoesntHave('cousre_enrollments');
                    });
                });
            })
            ->paths()
            ->get();
        return $purchased_learn_paths;
    }

    public function getPurchasedLearnPathsWhereInProgress(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $learn_paths_ids = $this->getModel()->whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $purchased_learn_paths = LearnPathInfo::whereIn('id', $learn_paths_ids)
            ->with(['allPathCertificates:id,learnpath_id,user_id'])
            ->when($user, function ($query) use ($user) {
                $query->with(['completedCourses' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }]);
            })
            ->whereDoesntHave('allPathCertificates', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('courses', function ($query) use ($user) {
                $query->whereHas('course', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->whereHas('cousre_enrollments', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        });
                    });
                });
            })
            ->paths()
            ->get();
        return $purchased_learn_paths;
    }

    public function getPurchasedLearnPathsWhereFinished(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $learn_paths_ids = $this->getModel()->whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $purchased_learn_paths = LearnPathInfo::whereIn('id', $learn_paths_ids)
            ->with(['allPathCertificates'])
            ->when($user, function ($query) use ($user) {
                $query->with(['completedCourses' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }]);
            })->when(!$user, function ($query) {
                $query->with('completedCourses');
            })
            ->whereHas('allPathCertificates', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->paths()
            ->get();
        return $purchased_learn_paths;
    }

    public function getPurchasedBundles(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $bundles_ids = $this->getModel()->whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $purchased_bundles = LearnPathInfo::whereIn('id', $bundles_ids)
            ->bundles()
            ->get();

        return $purchased_bundles;
    }

    public function getPurchasedBundlesWhereNotFinishedOrEnrolled(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $bundles_ids = $this->getModel()->whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $purchased_bundles = LearnPathInfo::whereIn('id', $bundles_ids)
            ->with(['allPathCertificates', 'courses'])
            ->whereDoesntHave('allPathCertificates', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('courses', function ($query) use ($user) {
                $query->whereHas('course', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->whereDoesntHave('cousre_enrollments', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        });
                    });
                });
            })
            ->bundles()
            ->get();

        return $purchased_bundles;
    }

    public function getPurchasedBundlesWhereInProgress(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $bundles_ids = $this->getModel()->whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $purchased_bundles = LearnPathInfo::whereIn('id', $bundles_ids)
            ->with(['allPathCertificates', 'courses'])
            ->when($user, function ($query) use ($user) {
                $query->with(['completedCourses' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }]);
            })->when(!$user, function ($query) {
                $query->with('completedCourses');
            })
            ->whereDoesntHave('allPathCertificates', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('courses', function ($query) use ($user) {
                $query->whereHas('course', function ($query) use ($user) {
                    $query->where(function ($query) use ($user) {
                        $query->whereHas('cousre_enrollments', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        });
                    });
                });
            })
            ->bundles()
            ->get();

        return $purchased_bundles;
    }

    public function getPurchasedBundlesWhereFinished(User $user)
    {
        $subscription_ids =  $user->purchased_subscription()
            ->where('expired_at', '>=', Carbon::now()->format('Y-m-d'))
            ->pluck('package_id')
            ->toArray();

        $bundles_ids = $this->getModel()->whereIn('id', $subscription_ids)
            ->whereNotNull('learn_path_id')
            ->pluck('learn_path_id');

        $purchased_bundles = LearnPathInfo::whereIn('id', $bundles_ids)
            ->whereHas('allPathCertificates', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->bundles()
            ->get();

        return $purchased_bundles;
    }
}
