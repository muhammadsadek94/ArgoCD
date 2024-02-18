<?php


namespace App\Domains\Enterprise\Repositories;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Events\Course\CourseCompleted;
use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\CourseReview;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use App\Domains\Course\Models\WatchingHistory;
use App\Foundation\Repositories\RepositoryInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class UserEnterpriseRepository extends Repository implements RepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }



    /**
     * Enterprise Users
     */

    /**
     * @param $admin_id
     * @return int
     */
    public function getEnterpriseUsers(Request $request, $admin_id, $joinTable = 'watch_history_times',  $sortByTable = 'sum( watch_history_times.watched_time)')
    {

        $perPage = $request->perPage != null ? $request->perPage : 10;
        $query = $this->getModel()->newQuery();
        //        $query = $query->where('activation',">=", 0);
        $query = $query->where(function ($query) use ($admin_id) {
            $query->where('users.enterprise_id', $admin_id)
                ->orWhere('users.subaccount_id', $admin_id);
        });
        $query = $query->where('users.type', UserType::USER);
        $query = $query->when($request->search, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->where('users.first_name', "LIKE", "%{$request->search}%")
                    ->orWhere->where('users.email', "LIKE", "%{$request->search}%");
            })->orderBy('users.created_at');
        });
        if (isset($request->status)) {
            if ($request->status != UserActivation::PENDING) {
                $query->where('users.activation', $request->status);
            } else {
                $query->where('users.activation', '>=', $request->status);
            }
        }
        $query->leftJoin($joinTable, function ($join) use ($joinTable, $request) {
            $join->on($joinTable . '.user_id', '=', 'users.id');
            $join->when($request->start_date, function ($query) use ($request, $joinTable) {
                return $query->where("{$joinTable}.created_at", ">=", $request->start_date);
            });
            $join->when($request->end_date, function ($query) use ($request, $joinTable) {
                $end_date = Carbon::parse($request->end_date)->endOfDay();
                return $query->whereDate("{$joinTable}.created_at", "<=", $end_date);
            });
        });





        if (isset($request->tag) && $request->tag != 'null') {
            $query->leftJoin('user_user_tag', function ($join) use ($joinTable) {
                $join->on('user_user_tag.user_id', '=', 'users.id');
            });
            $query->where('user_user_tag.user_tag_id', $request->tag);
        }


        if (isset($request->subaccount_id) && $request->subaccount_id != 'null') {
            $query->where('users.subaccount_id', $request->subaccount_id);
        }
        // $query = $this->getSearchWithTIme($query, $request, $joinTable);
        $column = $this->getCorrectColumn($sortByTable);

        if ($request->has('sort_by')) {

            $sortByTable = $this->getSortColumn($request->sort_by);

            if (!empty($sortByTable['table'])) {
                $query->leftJoin($sortByTable['table'] . ' as sortTable', function ($join) use ($sortByTable) {

                    $join->on('sortTable.user_id', '=', 'users.id');
                });
                $query = $query->selectRaw("users.* , {$sortByTable['query']} as sortColumn , $column");
            } else {
                $query = $query->selectRaw("users.* , users.{$sortByTable['query']} as sortColumn , $column");
            }
        } else {
            $query = $query->selectRaw("users.* , {$sortByTable} as sortColumn , $column");
        }
        $query = $query->groupBy('users.id');
        $query->orderBy('sortColumn',  $request->direction ?? 'desc');
        //    });
        // $query->orderBy('created_at');

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
         * */

        switch ($column) {
            case 'email':
                return ['query' => 'email', 'table' => ''];
            case 'name':
                return ['query' => 'first_name', 'table' => ''];
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
        }
    }

    private function getCorrectColumn($column)
    {
        if ($column == 'sum( watch_history_times.watched_time)') {
            return "$column as mins_watched";
        } elseif ($column == 'count(course_enrollment.id)') {
            return "$column as enrollment";
        } elseif ($column == 'avg (completed_courses.degree)') {
            return "$column as average_score";
        } elseif ($column == 'count(completed_courses.id)') {
            return "$column as completed_course";
        }
    }


    public function getLastActivitiesForUsers(Request $request, $admin_ids, $days = 30)
    {
        if ($days > 30 || $days < 2) {
            $days = 30;
        }
        $days_array = [];
        for ($i = 0; $i <= $days; $i++) {
            array_push($days_array, Carbon::today()->subDays($i)->format('d M'));
        }

        $query = WatchHistoryTime::whereIn('user_id', $admin_ids);
        $query = $query
            ->where('created_at', '>=', now()->subDays($days))
            ->whereHas('course', function ($query) {
                $query->where('course_type', CourseType::COURSE);
            })
            ->groupBy('lesson_id');
        $query = $query->get();
        // dd($query);
        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($days_array as $index => $date) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' => $grouped_data->first(function ($i, $k) use ($date) {
                    return $k === $date;
                }) ?
                    number_format($grouped_data[$date]->sum('watched_time') / 60, 2, '.', '')
                    : 0
            ]);
        }

        return $activities;
    }

    public function getLastActivitiesForUsersForSubAccount(Request $request, $admin_id, $subAccount, $days = 30)
    {
        if ($subAccount) {
            $query = $this->getUsersQuery($request, $admin_id);
            $query = $query->where('subaccount_id', $subAccount);
            $users_ids = $query->get()->pluck('id');
            // dd($subAccount , $admin_id);
            $query = WatchHistoryTime::whereIn('user_id', $users_ids);
            $query = $query->where('created_at', '>=', now()->subDays($days))
                ->whereHas('course', function ($query) {
                    $query->where('course_type', CourseType::COURSE);
                })
                ->groupBy('lesson_id');
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

                    // 'count' => $items->count()
                ]);
            }

            return $activities;
        } else
            return 0;
    }

    public function getEnterpriseUsersCharts(Request $request, $admin_id)
    {
        $query = $this->getModel()->newQuery();
        //        $query = $query->where('activation',">=", 0);
        $query = $query->where(function ($query) use ($admin_id) {
            $query->where('enterprise_id', $admin_id)
                ->orWhere('subaccount_id', $admin_id);
        });
        $query = $query->where('type', UserType::USER);

        $query = $this->getSearchWithTIme($query, $request);

        if (isset($request->status)) {
            if ($request->status != UserActivation::PENDING) {
                $query->where('activation', $request->status);
            } else {
                $query->where('activation', '>=', $request->status);
            }
        }
        $query->orderBy('created_at');


        $query = $query->get();

        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' => $items->count()
            ]);
        }

        return $activities;
    }


    public function getEnterpriseCoursesCharts(Request $request, $admin_id)
    {
        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $course_ids = CourseEnrollment::whereIn('user_id', $query)->get()->pluck('id');
        $query = Course::whereIn('id', $course_ids);
        $query = $this->getSearchWithTIme($query, $request);

        $query = $query->get();
        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' => $items->count()
            ]);
        }

        return $activities;
    }

    public function getEnterpriseMinsWatchedCharts(Request $request, $admin_id)
    {

        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $query = WatchHistoryTime::whereIn('user_id', $query);

        $query = $this->getSearchWithTIme($query, $request);

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


    public function getEnterpriseTopWatchedCourses(Request $request, $admin_ids)
    {
        $grouped_data = WatchHistoryTime::whereIn('user_id', $admin_ids)->latest('created_at')->limit(1000)->get()->groupBy('course_id');
        //    dd(count($grouped_data));
        $courses = [];
        foreach ($grouped_data as $row) {
            array_push($courses, [
                'course_name' => $row[0]->course ? $row[0]->course->name : $row[0]->course->id,
                'time' =>
                number_format($row->sum('watched_time') / 60, 2, '.', '')
            ]);
        }
        $courses = collect($courses)->sortBy('time')->take(5)->reverse()->values()->toArray();
        return $courses;
    }


    public function getEnterpriseEnrolledCoursesCharts(Request $request, $admin_id)
    {

        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $query = CourseEnrollment::whereIn('user_id', $query);
        $query = $this->getSearchWithTIme($query, $request);
        $query = $query->get();
        $grouped_data = $query->groupBy(function ($row) {
            return $row->created_at->format('d M');
        });

        $activities = [];

        foreach ($grouped_data as $date => $items) {
            array_push($activities, [
                'date' => Carbon::parse($date)->timestamp,
                'count' => $items->count()
            ]);
        }

        return $activities;
    }

    public function getEnterpriseCompletionRateCharts(Request $request, $admin_id)
    {

        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $query = CompletedCoursePercentage::whereIn('user_id', $query);
        $query = $this->getSearchWithTIme($query, $request);
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

        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $query = CompletedCourses::whereIn('user_id', $query);
        $query = $this->getSearchWithTIme($query, $request);
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

        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $query = CompletedCourses::whereIn('user_id', $query);
        $query = $this->getSearchWithTIme($query, $request);
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


    public function getEnterpriseCourseSatisfaction(Request $request, $admin_ids)
    {
        $query = CourseReview::whereIn('user_id', $admin_ids);
        return ($query->average('rate') / 5) * 100;
    }


    //    start statistics
    public function getEnterpriseTotalUser(Request $request, $admin_id, $status)
    {

        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->where('activation', $status);
        if ($status != UserActivation::PENDING) {
            $query->where('activation', $status);
        } else {
            $query->where('activation', '>=', $status);
        }
        return $query->count();
    }

    public function getEnterpriseTotalInactiveUser(Request $request, $admin_id, $status)
    {

        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->where('activation', '0');
        return $query->count();
    }

    public function getEnterpriseAverageScore(Request $request, $admin_ids)
    {

        $query = CompletedCourses::whereIn('user_id', $admin_ids);
        $query = $this->getSearchWithTImeForTableStatistics($query, $request);

        return $query->get()->average('degree');
    }

    public function getTotalCourseEnrollments($user_ids)
    {
        return CourseEnrollment::whereIn('user_id', $user_ids)->count();
    }

    public function getEnterpriseAverageRate(Request $request, $admin_ids)
    {
        $query = CourseReview::whereIn('user_id', $admin_ids)
            ->where(function ($query) {
                $query->where('rate', '>', 0)
                    ->orWhereNotNull('rate');
            });
        $query = $this->getSearchWithTImeForTableStatistics($query, $request);

        return $query->get()->average('rate');
    }

    public function getEnterpriseAverageCompletionRate(Request $request, $admin_ids)
    {
        $query = CompletedCoursePercentage::whereIn('user_id', $admin_ids);
        $query = $this->getSearchWithTImeForTableStatistics($query, $request);

        return $query->get()->average('completed_percentage');
    }

    public function getEnterpriseTotalMinWatchedWithTimeFiltration(Request $request, $admin_ids)
    {
        $query = WatchHistoryTime::whereIn('user_id', $admin_ids);
        $query = $this->getSearchWithTImeForTableStatistics($query, $request);

        return $query->get()->sum('watched_time') / 60;
    }

    public function getEnterpriseTotalMinWatched(Request $request, $admin_ids)
    {
        $query = WatchHistoryTime::whereIn('user_id', $admin_ids);

        return $query->sum('watched_time') / 60;
    }

    public function getEnterpriseTotalCompletedCourse(Request $request, $user_ids)
    {

        return CompletedCourses::whereIn('user_id', $user_ids)->count();
    }


    public function getEnterpriseTotalEnrollment(Request $request, $admin_ids)
    {

        $admin_id = $request->user()->id;

        
        $query = $this->getEnterpriseCoursesQueryBasic($admin_id);
        $query = $query->leftJoin("course_enrollment", function ($join) {
            $join->on("course_enrollment.course_id", "=", "courses.id");
        });
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "course_enrollment.user_id");
        });
        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $this->getSearchWithTImeAdvanced($query, $request, 'course_enrollment');

        $query = $query->select(\DB::raw('course_enrollment.*'));

        return $query->get()->count();
    }

    public function getEnterpriseTotalEnrollmentForSubAccounts(Request $request, $admin_ids)
    {

        $admin_id = $request->user()->id;

        
        $query = $this->getEnterpriseCoursesQueryBasic($admin_id);
        $query = CourseEnrollment::whereIn('user_id', $admin_ids);
        $query = $query->join("users", function ($join) {
            $join->on("users.id", "=", "course_enrollment.user_id");
        });
        $query = $this->onlyEnterpriseUsersDataQuery($query, $admin_id);
        $query = $this->getSearchWithTImeAdvanced($query, $request, 'course_enrollment');

        $query = $query->select(\DB::raw('course_enrollment.*'));

        return $query->get()->count();
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


    public function onlyEnterpriseUsersDataQuery($query, $admin_id)
    {
        return $query->where(function ($query) use ($admin_id) {
            $query->where('users.enterprise_id', $admin_id)
                ->orWhere('users.subaccount_id', $admin_id);
        });
    }


    public function getEnterpriseTotalCompleted(Request $request, $admin_ids)
    {
        $query = CompletedCourses::whereIn('user_id', $admin_ids);
        $query = $this->getSearchWithTImeForTableStatistics($query, $request);
        return $query->get()->count();
    }


    public function getEnterpriseAverageEnrollment(Request $request, $admin_ids)
    {

        //        $query = $this->getUsersQuery($request, $admin_id);
        //        $query = $query->get()->pluck('id');
        $query_enrolment = CourseEnrollment::whereIn('user_id', $admin_ids);
        $query_enrolment = $this->getSearchWithTImeForTableStatistics($query_enrolment, $request);
        $total_users = count($admin_ids) > 0 ? count($admin_ids) : 1;
        return $query_enrolment->get()->count() / $total_users;
    }


    public function getEnterpriseSubAccountAverageEnrollment(Request $request, $admin_id)
    {
        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $query_enrolment = CourseEnrollment::whereIn('user_id', $query);
        $query_enrolment = $this->getSearchWithTImeForTableStatistics($query_enrolment, $request);
        $count = $this->getSubAccountQuery($request, $admin_id)->count();
        //        $total_subAccount = $count ? $count : 1;
        return $count > 0 ? $query_enrolment->get()->count() / $count : 0;
    }

    //    end statistics

    public function getUsersQuery(Request $request, $admin_id)
    {
        $query = $this->getModel()->newQuery();
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

    public function getSubaccountsUsersQuery(Request $request, $admin_id)
    {
        $query = $this->getModel()->newQuery();
        //        $query = $query->where('activation',">=", 0);
        $query = $query->where(function ($query) use ($admin_id) {
            $query->whereIn('subaccount_id', $admin_id);
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
    public function getSubAccountQuery(Request $request, $admin_id)
    {
        $query = $this->getModel()->newQuery();
        //        $query = $query->where('activation',">=", 0);
        $query = $query->where(function ($query) use ($admin_id) {
            $query->where('enterprise_id', $admin_id)
                ->orWhere('subaccount_id', $admin_id);
        });
        $query->whereIn('type', [UserType::REGULAR_ENTERPRISE_SUBACCOUNT, UserType::PRO_ENTERPRISE_SUBACCOUNT]);
        return $query;
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



    private function getSearchWithTIme($query, $request)
    {
        if (isset($request->start_date) || isset($request->end_date)) {

            $query = $query->when($request->start_date, function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    return $query->where('created_at', ">=", $request->start_date);
                });
            });

            $query = $query->when($request->end_date, function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $end_date = new DateTime($request->end_date);
                    $end_date = $end_date->modify('+1 day');
                    return $query->whereDate('created_at', "<=", $end_date);
                });
            });
        } else {
            // $query = $query->where('created_at', ">=", Carbon::now()->subMonth()->format('Y-m-d'));
        }
        return $query;
    }


    public function getSearchWithTImeForTableStatistics($query, $request)
    {
        if (isset($request->start_date) || isset($request->end_date)) {

            $query = $query->when($request->start_date, function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    return $query->whereDate('created_at', ">=", $request->start_date);
                });
            });

            $query = $query->when($request->end_date, function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $end_date = new DateTime($request->end_date);
                    $end_date = $end_date->modify('+1 day');
                    return $query->whereDate('created_at', "<=", $end_date);
                });
            });
        } else {
            //            $query = $query->where('created_at', ">=", Carbon::now()->subMonth()->format('Y-m-d'));
        }
        return $query;
    }

    public function getTotalUserGrowth($request, $admin_ids): int
    {

        $last_30_day = Carbon::now()->subMonth()->format('Y-m-d');
        $last_60_day = Carbon::now()->subMonths(2)->format('Y-m-d');

        $current = WatchHistoryTime::where('updated_at', ">=", $last_30_day)
            ->whereIn('user_id', $admin_ids)
            ->sum('watched_time');

        $last = WatchHistoryTime::whereBetween('updated_at', [$last_60_day, $last_30_day])
            ->whereIn('user_id', $admin_ids)
            ->sum('watched_time');

        return $this->getIncrementPercentage($current, $last);
    }

    public function getTotalScoreGrowth($request, $admin_id): int
    {
        $query = $this->getUsersQuery($request, $admin_id);
        $query = $query->get()->pluck('id');
        $query = CompletedCourses::whereIn('user_id', $query);

        $last_30_day = Carbon::now()->subMonth()->format('Y-m-d');
        $last_60_day = Carbon::now()->subMonths(2)->format('Y-m-d');

        $current = clone ($query);
        $current = $current->where('created_at', ">=", $last_30_day)
            ->average('degree');
        $last = $query
            ->whereBetween('created_at', [$last_60_day, $last_30_day])
            ->average('degree');
        return $this->getIncrementPercentage($current ? $current : 0, $last ? $last : 0);
    }

    private function getIncrementPercentage(int $new_number, int $original_number): int
    {
        if ($original_number == 0) return 0;
        $increase = $new_number - $original_number;
        $increase_percentage = $increase / $original_number * 100;

        return $increase_percentage;
    }


    public function getEnterpriseSubAccount(Request $request, $admin_id, $per_page = null)
    {

        $perPage = $request->perPage != null ? $request->perPage : 10;
        if ($per_page) {
            $perPage = $per_page;
        }
        $query = $this->getModel()->newQuery();
        //        $query = $query->where('activation',">=", 0);
        $query = $query->where(function ($query) use ($admin_id) {
            $query->where('enterprise_id', $admin_id)
                ->orWhere('subaccount_id', $admin_id);
        });
        $query->whereIn('type', [UserType::REGULAR_ENTERPRISE_SUBACCOUNT, UserType::PRO_ENTERPRISE_SUBACCOUNT]);
        $query = $query->when($request->search, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->where('company_name', "LIKE", "%{$request->search}%")
                    ->orWhere->where('email', "LIKE", "%{$request->search}%");
            });
        });
        return $query->paginate($perPage);
    }

    public function getEnterpriseSubAccountWithUsers(Request $request, $admin_id, $per_page = null)
    {

        $perPage = $request->perPage != null ? $request->perPage : 10;
        if ($per_page) {
            $perPage = $per_page;
        }

        $query = $this->getModel()->newQuery();
        //        $query = $query->where('activation',">=", 0);
        $query->where('users.id', '=', $admin_id);
        $query->leftJoin('users as subaccounts', function ($join) {
            $join->on('subaccounts.enterprise_id', '=', 'users.id')
                ->whereIn('subaccounts.type', [UserType::REGULAR_ENTERPRISE_SUBACCOUNT, UserType::PRO_ENTERPRISE_SUBACCOUNT]);
        });


        if ($request->has('sort_by')) {

            $sortByTable = $this->getSortColumn($request->sort_by);

            if (!empty($sortByTable['table'])) {
                $query->leftJoin('users as trainees', 'trainees.subaccount_id', '=', 'subaccounts.id');

                $query->leftJoin($sortByTable['table'] . ' as sortTable', function ($join) use ($request) {
                    $join->on('sortTable.user_id', '=', 'trainees.id');
                    $join->when($request->start_date, function ($query) use ($request) {
                        return $query->where("sortTable.created_at", ">=", $request->start_date);
                    });
                    $join->when($request->end_date, function ($query) use ($request) {
                        $end_date = Carbon::parse($request->end_date)->endOfDay();
                        return $query->whereDate("sortTable.created_at", "<=", $end_date);
                    });
                });


                $query = $query->selectRaw("{$sortByTable['query']} as sortColumn ");
                $query->groupBy('subaccounts.id');
                $query->orderByRaw('sortColumn ' .  $request->direction ?? 'desc');
            } elseif ($request->sort_by == 'name') {

                $query = $query->selectRaw("subaccounts.company_name as sortColumn ");
                $query->orderBy('sortColumn',  $request->direction ?? 'desc');
            } else {

                $query = $query->selectRaw("subaccounts.{$sortByTable['query']} as sortColumn ");
                $query->orderBy('sortColumn',  $request->direction ?? 'desc');
            }
        }


        $query = $query->when($request->search, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->where('subaccounts.company_name', "LIKE", "%{$request->search}%")
                    ->orWhere->where('subaccounts.email', "LIKE", "%{$request->search}%");
            });
        });
        $query = $query->selectRaw("subaccounts.* ");
        return $query->paginate($perPage);
    }

    public function getEnterpriseUserById($admin_id, $user_id)
    {
        $query = $this->getModel()->newQuery();
        $user = $query->where(function ($query) use ($admin_id) {
            $query->where('enterprise_id', $admin_id)
                ->orWhere('subaccount_id', $admin_id);
        })->where('id', $user_id)->firstOrFail();
        return $user;
    }
}
