<?php


namespace App\Domains\Enterprise\Repositories;


use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Enterprise\Services\Users\EnterpiseUserTrait;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use App\Foundation\Repositories\RepositoryInterface;
use Barryvdh\Reflection\DocBlock\Type\Collection;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use DB;

class CourseEnterpriseRepository extends Repository implements RepositoryInterface
{
    use EnterpiseUserTrait;
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getEnterpriseFilteredCourses(Request $request, $admin_id, $joinTable = 'watch_history_times', $sortByTable = 'sum( watch_history_times.watched_time)')
    {
        $perPage = $request->perPage != null ? $request->perPage : 10;

        $query = $this->getEnterpriseCoursesQuery($request, $admin_id);



        if ($joinTable != 'course_enrollment') {
//            $query->leftJoin('course_enrollment', function ($join) use ($joinTable, $request) {
//                $join->on('course_enrollment.course_id', '=', 'courses.id');
////                $join = $this->getSearchWithTIme($join, $request, $joinTable);
//            });
            $query->with(['cousre_enrollments_by_enterprise' => function ($query) use ($admin_id) {
                $query->where('enterprise_id', $admin_id)
                    ->orWhere('subaccount_id', $admin_id);;
            }]);

        }



        $query->with([
            'cousre_enrollments_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'course_enrollment', $admin_id);
            },
            'completed_course_percentages_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'completed_course_percentages', $admin_id);
            },
            'watch_history_time_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'watch_history_times', $admin_id);

            },
            'completed_course_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'completed_courses', $admin_id);
            },
            'course_review_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'course_reviews', $admin_id);
            }
        ]);

        $query = $this->filterationFn($query, $request);
        if (!in_array($joinTable, ['completed_courses', 'course_reviews'])){
            $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        }

        if ($request->has('sort_by')) {

            $sortByTable = $this->getSortColumn($request->sort_by);

            if(!empty($sortByTable['table'])) {
//                $query->leftJoin($sortByTable['table'], function ($join) use ($sortByTable, $request) {
//                    $join->on($sortByTable['table'] . '.course_id', '=', 'courses.id');
//            $join = $this->getSearchWithTIme($join, $request, $joinTable);
//                });
                $query->leftJoin('users', function ($join) use ($admin_id, $sortByTable) {
                    $join->on('users.enterprise_id', '=', DB::raw("'$admin_id'"))
                    ->orWhere('users.subaccount_id', '=', DB::raw("'$admin_id'"));
                });
                $query->leftJoin($sortByTable['table'].' as sortTable', function ($join) use ($sortByTable, $request) {

                    $join->on( 'sortTable.course_id', '=', 'courses.id');
                    $join->on( 'sortTable.user_id', '=', 'users.id');
                    $this->getSearchWithTIme($join, $request, 'sortTable');

                });
                $query = $query->selectRaw("COALESCE({$sortByTable['query']}, 0) as sortColumn, courses.*  ");
            }else{
                $query = $query->selectRaw("courses.{$sortByTable['query']} as sortColumn, courses.*  ");
            }

        }else {
            $query->leftJoin($joinTable, function ($join) use ($joinTable, $request) {
                $join->on($joinTable . '.course_id', '=', 'courses.id');
//            $join = $this->getSearchWithTIme($join, $request, $joinTable);
            });
            $query->leftJoin('users', function ($join) use ($joinTable) {
                $join->on($joinTable . '.user_id', '=', 'users.id');
            });
            $query = $query->selectRaw( $sortByTable . ' as sortColumn, courses.*');
        }

        $query = $query->groupBy('courses.id');
        $query->orderBy('sortColumn', $request->direction ?? 'desc');
//          $query->dd();
//        dd($query->get()->toArray());
        return $query->paginate($perPage);
    }

    private function getSortColumn($column)
    {
        /*
         * name
         * Avg. Score Results
         * Mins. Watched
         * completion rate
         * date added
         * enrollments
         * completed courses
         * status
         * avg rate
         * */

        switch ($column){
            case 'course':
                return ['query' => 'name', 'table' => ''];
            case 'Avg. Score Results':
                return ['query' => 'avg (sortTable.degree)', 'table' => 'completed_courses'];
            case 'completion rate':
                return ['query' => 'avg (sortTable.completed_percentage)', 'table' => 'completed_course_percentages'];
            case 'date added':
                return ['query' => 'created_at', 'table' => ''];
            case 'enrollments':
                return ['query' => 'count(sortTable.id)', 'table' => 'course_enrollment'];
            case 'completed courses':
                return ['query' => 'count(sortTable.id)', 'table' => 'completed_courses'];
            case 'status':
                return ['query' => 'activation', 'table' => ''];
            case 'Mins. Watched':
                return ['query' => 'sum( sortTable.watched_time)', 'table' => 'watch_history_times'];
            case 'avg rate':
                return ['query' => 'avg( sortTable.rate)', 'table' => 'course_reviews'];
        }
    }

    public function getEnterpriseCourses(Request $request, $admin_id, $joinTable = 'watch_history_times', $sortByTable = 'sum( watch_history_times.watched_time)')
    {
        $perPage = $request->perPage != null ? $request->perPage : 10;

        $query = $this->getEnterpriseCoursesQuery($request, $admin_id);

        $query->leftJoin($joinTable, function ($join) use ($joinTable, $request) {
            $join->on($joinTable . '.course_id', '=', 'courses.id');
//            $join = $this->getSearchWithTIme($join, $request, $joinTable);
        });

        if ($joinTable != 'course_enrollment') {
//            $query->leftJoin('course_enrollment', function ($join) use ($joinTable, $request) {
//                $join->on('course_enrollment.course_id', '=', 'courses.id');
////                $join = $this->getSearchWithTIme($join, $request, $joinTable);
//            });
            $query->with(['cousre_enrollments_by_enterprise' => function ($query) use ($admin_id) {
                $query->where('enterprise_id', $admin_id)
                    ->orWhere('subaccount_id', $admin_id);;
            }]);

        }

        $query->leftJoin('users', function ($join) use ($joinTable) {
            $join->on($joinTable . '.user_id', '=', 'users.id');
        });

        $query->with([
            'cousre_enrollments_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'course_enrollment', $admin_id);
            },
            'completed_course_percentages_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'completed_course_percentages', $admin_id);
            },
            'watch_history_time_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'watch_history_times', $admin_id);

            },
            'completed_course_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'completed_courses', $admin_id);
            },
            'course_review_by_enterprise' => function ($query) use ($admin_id, $request) {
                return $this->getEnterpriseSearchWithTIme($query, $request, 'course_reviews', $admin_id);
            }
        ]);

        $query = $this->filterationFn($query, $request);
        if (!in_array($joinTable, ['completed_courses', 'course_reviews'])){
            $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        }

        $query = $query->selectRaw('courses.* , ' . $sortByTable . ' as sortColumn');
        $query = $query->groupBy('courses.id');
        $query->orderBy('sortColumn', 'desc');
        //  $query->dd();
        return $query->paginate($perPage);
    }
    public function getEnterpriseCoursesQuery(Request $request, $admin_id)
    {
        $query = $this->getEnterpriseCoursesQueryBasic($admin_id);
        // $query = $query->when($request->search, function ($query) use ($request) {
        //         return $query->where('courses.name', "LIKE", "%{$request->search}%");
        // });

        $query = $query->when($request->status, function ($query) use ($request) {
            return $query->where('users.activation', $request->status);
        });
        return $query;
    }



    public function getEnterpriseCoursesQueryBasic($admin_id)
    {
        $query = Course::join('course_enrollment', function ($join) {
            $join->on('courses.id', '=', 'course_enrollment.course_id');
        })->join('users', function ($join) {
            $join->on('users.id', '=', 'course_enrollment.user_id');
        })->where(function ($query) use ($admin_id) {
            $query->where('users.enterprise_id', $admin_id);
            $query->orWhere('users.subaccount_id', $admin_id);
        })->pluck('courses.id');
        $query = Course::whereIn('courses.id', $query);

        $query = $query->where('courses.activation', '>', 0);
        return $query;
    }

    public function getEnterpriseOnlyAssignedCoursesQuery(Request $request, $admin_id)
    {
        $packages = PackageSubscription::join('enterprise_learn_paths', function ($join) {
            $join->on('enterprise_learn_paths.package_id', '=', 'package_subscriptions.id');
        })->where(function ($query) use ($admin_id) {
            $query->where('enterprise_learn_paths.enterprise_id', $admin_id);
        })->get();
        $ids = [];
        foreach ($packages as $package) {
            $ids = array_merge($ids, Collect(json_decode($package->access_id, true))->toArray());
        }
        $courses = Course::whereIn('courses.id', $ids);
        $query = $courses;
        //        $query = $query->where('activation','>', 0);
        $query = $query->when($request->search, function ($query) use ($request) {
            return $query->where('name', "LIKE", "%{$request->search}%");
        });
        $query = $query->when($request->status, function ($query) use ($request) {
            return $query->where('activation', $request->status);
        });
        return $query;
    }
    public function onlyEnterpriseUsersDataQuery($query, $admin_id)
    {

        return $query->where(function ($query) use ($admin_id) {
            $query->where('users.enterprise_id', $admin_id)
                ->orWhere('users.subaccount_id', $admin_id)
                ->orwhereNull('users.id');
        });
    }

    public function getEnterpriseRatingCharts(Request $request, $admin_id)
    {

        $query = $this->getEnterpriseCoursesQuery($request, $admin_id);
        $query = $query->join("course_reviews", function ($join) {
            $join->on("course_reviews.course_id", "=", "courses.id");
        });
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "course_reviews.user_id");
        });
        $query = $this->getSearchWithTIme($query, $request, 'course_reviews');

        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $query->select(DB::raw('course_reviews.*'));
        $query = $query->get();

        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' => $items->average('rate')
            ]);
        }

        return $activities;
    }


    public function getEnterpriseEnrolledCoursesCharts(Request $request, $admin_id)
    {

        $admin = auth()->user();
        $user_query = $this->getUsersQuery($request, $admin->id);
        $user_ids = $user_query->pluck('id');
        
        $admin_id = $request->user()->id;

        
        $query = $this->getEnterpriseCoursesQueryBasic($admin_id);
        $query = CourseEnrollment::whereIn('user_id', $user_ids);
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "course_enrollment.user_id");
        });
        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $this->getSearchWithTImeAdvanced($query, $request, 'course_enrollment');

        $query = $query->select(\DB::raw('course_enrollment.*'));

        $query = $query->get();
        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = []; //        $grouped_data =[];
        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' => $items->count()
            ]);
        }

        return $activities;
    }

    private function getSearchWithTImeAdvanced($query, $request, $sortBy)
    {
        if (isset($request->start_date) || isset($request->end_date)) {

            $query = $query->when($request->start_date, function ($query) use ($request, $sortBy) {
                return $query->where(
                    function ($q) use ($request, $sortBy) {
                        return $q->where($sortBy . '.created_at', ">=", $request->start_date)
                            ->orWhereNull($sortBy . '.id');
                    }
                );
            });

            $query = $query->when($request->end_date, function ($query) use ($request, $sortBy) {
                return $query->where(
                    function ($q) use ($request, $sortBy) {

                        $end_date = new DateTime($request->end_date);
                        $end_date = $end_date->modify('+1 day');
                        return $q->where($sortBy . '.created_at', "<=", $end_date)
                            ->orWhereNull($sortBy . '.id');
                    }
                );
            });
        } else {
            // $query = $query->where('created_at', ">", Carbon::now()->subMonth()->format('Y-m-d'));
        }
        return $query;
    }

    public function getUsersQuery(Request $request, $admin_id)
    {
        $query = User::query();
        //        $query = $query->where('activation',">=", 0);
        $query = $query->where(function ($query) use ($admin_id) {
            $query->where('enterprise_id', $admin_id)
                ->orWhere('subaccount_id', $admin_id);
        });
        $query = $query->where('type', UserType::USER);

        if (isset($request->status)) {
            if ($request->status != UserActivation::PENDING) {
                $query->where('activation', $request->status);
            } else {
                $query->where('activation', '>=', $request->status);
            }
        }

        $query->orderBy('created_at');

        return $query;
    }

    public function getEnterpriseCompletionRateCharts(Request $request, $admin_id)
    {
        $query = $this->getEnterpriseCoursesQuery($request, $admin_id);
        $query = $query->join("completed_course_percentages", function ($join) {
            $join->on("completed_course_percentages.course_id", "=", "courses.id");
        });
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "completed_course_percentages.user_id");
        });
        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $query->select(DB::raw('completed_course_percentages.*'));
        $query = $this->getSearchWithTIme($query, $request, 'completed_course_percentages');

        $query = $query->get();
        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' =>
                number_format($items->average('completed_percentage'), 2, '.', '')


            ]);
        }

        return $activities;
    }


    public function getEnterpriseCompletedCourseCharts(Request $request, $admin_id)
    {

        $query = $this->getEnterpriseCoursesQuery($request, $admin_id);
        $query = $query->join("completed_courses", function ($join) {
            $join->on("completed_courses.course_id", "=", "courses.id");
        });
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "completed_courses.user_id");
        });
        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $this->getSearchWithTIme($query, $request, 'completed_courses');

        $query = $query->select(DB::raw('completed_courses.*'));
        $query = $query->get();
        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' =>
                number_format($items->count(), 2, '.', '')


            ]);
        }

        return $activities;
    }


    public function getEnterpriseScoreCharts(Request $request, $admin_id)
    {

        $query = $this->getEnterpriseCoursesQuery($request, $admin_id);
        $query = $query->join("completed_courses", function ($join) {
            $join->on("completed_courses.course_id", "=", "courses.id");
        });
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "completed_courses.user_id");
        });
        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $query->select(DB::raw('completed_courses.*'));
        $query = $this->getSearchWithTIme($query, $request, 'completed_courses');
        $query = $query->get();
        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' => $items->avg('degree')
            ]);
        }

        return $activities;
    }

    public function getEnterpriseMinsWatchedCharts(Request $request, $admin_id)
    {

        $query = $this->getEnterpriseCoursesQuery($request, $admin_id);
        $query = $query->join("watch_history_times", function ($join) {
            $join->on("watch_history_times.course_id", "=", "courses.id");
        });
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "watch_history_times.user_id");
        });
        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $query->select(DB::raw('watch_history_times.*'));
        $query = $this->getSearchWithTIme($query, $request, 'watch_history_times');
        $query = $query->get();
        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' =>
                number_format($items->sum('watched_time') / 60, 2, '.', '')
            ]);
        }

        return $activities;
    }

    public function getEnterpriseTotalMinsWatched(Request $request, $admin_id)
    {

        $query = $this->getEnterpriseCoursesQuery($request, $admin_id);
        $query = $query->join("watch_history_times", function ($join) {
            $join->on("watch_history_times.course_id", "=", "courses.id");
        });
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "watch_history_times.user_id");
        });
        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $query->select(DB::raw('watch_history_times.*'));
        $query = $this->getSearchWithTIme($query, $request, 'watch_history_times');
        $query = $query->get();


        $activities = 0;

        foreach ($query as $items) {
                $activities += number_format((float)$items->watched_time / 60, 2, '.', '');
        }

        return $activities;
    }

    private function getSearchWithTIme($query, $request, $sortBy)
    {
        if (isset($request->start_date) || isset($request->end_date)) {

            $query = $query->when($request->start_date, function ($query) use ($request, $sortBy) {
                return $query->where(
                    function ($q) use ($request, $sortBy) {
                        return $q->where($sortBy . '.created_at', ">=", $request->start_date)
                            ->orWhereNull($sortBy . '.id');
                    }
                );
            });

            $query = $query->when($request->end_date, function ($query) use ($request, $sortBy) {
                return $query->where(
                    function ($q) use ($request, $sortBy) {

                        $end_date = new DateTime($request->end_date);
                        $end_date = $end_date->modify('+1 day');
                        return $q->where($sortBy . '.created_at', "<=", $end_date)
                            ->orWhereNull($sortBy . '.id');
                    }
                );
            });
        } else {
            // $query = $query->where('created_at', ">", Carbon::now()->subMonth()->format('Y-m-d'));
        }
        return $query;
    }

    private function getEnterpriseSearchWithTIme($query, $request, $table_name, $admin_id)
    {
        $query = $query->when($request->start_date, function ($query) use ($request, $table_name) {
            return $query->where(
                function ($q) use ($request, $table_name) {
                    return $q->where($table_name . '.created_at', ">=", $request->start_date);
                }
            );
        });

        $query = $query->when($request->end_date, function ($query) use ($request, $table_name) {
            return $query->where(
                function ($q) use ($request, $table_name) {
                    $end_date = new DateTime($request->end_date);
                    $end_date = $end_date->modify('+1 day');
                    return $q->where($table_name . '.created_at', "<=", $end_date);
                });
            });

        $query->where(function ($query) use ($admin_id){
            $query->where('enterprise_id', $admin_id)
                ->orWhere('subaccount_id', $admin_id);
        });

        return $query;
    }

    public function getEnterpriseAverageEnrollment(Request $request, $admin_id)
    {
        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $query_enrolment = CourseEnrollment::whereIn('user_id', $query);
        $query_enrolment = $this->getSearchWithTImeForTableStatistics($query_enrolment, $request);
        $total_courses = $this->getEnterpriseCoursesQueryBasic($admin_id)->count() ? $this->getEnterpriseCoursesQueryBasic($admin_id)->count() : 1;

        return $query_enrolment->get()->count() / $total_courses;
    }

    function filterationFn($search, $request)
    {
        $search = $search->when($request->search, function ($query) use ($request) {
            $query->leftJoin(
                'course_course_tag',
                function ($join) {
                    $join->on('courses.id', '=', 'course_course_tag.course_id');
                }
            );
            $query->leftJoin(
                'course_tags',
                function ($join) {
                    $join->on('course_tags.id', '=', 'course_course_tag.course_tag_id');
                }
            );
            return $query->where(
                function ($query) use ($request) {

                    return $query->where('courses.name', "LIKE", "%{$request->search}%")
                        ->orWhere('courses.brief', "LIKE", "%{$request->search}%")
                        ->orWhere('courses.description', "LIKE", "%{$request->search}%")
                        ->orWhere('course_tags.name', "LIKE", "%{$request->search}%");
                }
            );
        });

        $search = $search->when($request->tag, function ($query) use ($request) {
            $query->leftJoin(
                'course_course_tag',
                function ($join) {
                    $join->on('courses.id', '=', 'course_course_tag.course_id');
                }
            );
            $query->leftJoin(
                'course_tags',
                function ($join) {
                    $join->on('course_tags.id', '=', 'course_course_tag.course_tag_id');
                }
            );
            return $query->where(
                function ($query) use ($request) {

                    return $query->Where('course_tags.name', "LIKE", "%{$request->tag}%");
                }
            );
        });
        $search = $search->when($request->course_category_id && $request->course_category_id != 'category', function ($query) use ($request) {
            return $query->where(
                function ($query) use ($request) {
                    return $query->where('courses.course_category_id', $request->course_category_id)
                                    ->orWhere('courses.course_sub_category_id', $request->course_category_id);
                }
            );
        });

        $search = $search->when($request->level && $request->level != 'skill', function ($query) use ($request) {
            return $query->where(
                function ($query) use ($request) {
                    return $query->where('courses.level', $request->level);
                }
            );
        });

        $search = $search->when($request->has('start_timing') && $request->has('end_timing'), function ($query) use ($request) {
            return $query->where(
                function ($query) use ($request) {
                    $query->whereBetween('courses.timing', [(int)$request->start_timing, (int)$request->end_timing]);
                }
            );
        });
        return $search;
    }
}
