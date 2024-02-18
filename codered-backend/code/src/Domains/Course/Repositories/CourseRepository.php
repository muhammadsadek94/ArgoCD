<?php

namespace App\Domains\Course\Repositories;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\Lookups\CourseCategory;
use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use DB;
use Framework\Traits\SelectColumnTrait;

class CourseRepository extends Repository implements CourseRepositoryInterface
{

    use SelectColumnTrait;

    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function getFeaturedCourses(int $limit = 30, array $with = [], ?User $user = null): Collection
    {
        if ($user) {
            $category_id = $user->categories()->first() ? $user->categories()->first()->id : null;
        }

        $query = $this->getModel()->newQuery();
        $query
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->where('is_featured', 1)
            ->limit($limit)
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->with(
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            )
            ->inRandomOrder();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($category_id)) {
            $query = $query->where(function ($q) use ($category_id) {
                $q->where('course_sub_category_id', $category_id)
                    ->orWhere('course_category_id', $category_id);
            });
        }
        return $query->get();
    }

    public function bestSellersCategoryBased(int $limit = 30, array $with = [], ?User $user = null, ?CourseCategory $category = null): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->where('is_best_seller', 1)
            ->limit($limit)
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->inRandomOrder();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($category)) {
            $query = $query->where(function ($q) use ($category) {
                $q->where('course_sub_category_id', $category->id)
                    ->orWhere('course_category_id', $category->id);
            });
        }
        return $query->get();
    }

    public function editorialPicksCategoryBased(int $limit = 30, array $with = [], ?User $user = null, ?CourseCategory $category = null): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->where('is_editorial_pick', 1)
            ->limit($limit)
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->inRandomOrder();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($category)) {
            $query = $query->where(function ($q) use ($category) {
                $q->where('course_sub_category_id', $category->id)
                    ->orWhere('course_category_id', $category->id);
            });
        }
        return $query->get();
    }

    public function top10CategoryBased(array $with = [], ?User $user = null, ?CourseCategory $category = null): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->where('is_editorial_pick', 1)
            ->latest('courses.created_at')
            ->take(10)
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->inRandomOrder();
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($category)) {
            $query = $query->where(function ($q) use ($category) {
                $q->where('course_sub_category_id', $category->id)
                    ->orWhere('course_category_id', $category->id);
            });
        }
        return $query->get();
    }

    public function getFreeCourses(int $limit = 30, array $with = [], ?User $user = null): Collection
    {
        $query = $this->getModel()->newQuery();
        $query
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                'category.' . SelectColumnTrait::$imageColumnsInline,
                'sub.' . SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$menuCoverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->where('is_free', 1)
            ->latest('created_at')
            ->limit($limit);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function getEssentialCourses(int $limit = 30, array $with = []): Collection
    {
        $query = $this->getModel()->newQuery();
        $query
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->where('is_essential', 1)
            ->latest('created_at')
            ->with(
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            )
            ->limit($limit);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function getMicrodegrees(int $limit = 3, array $with = []): Collection
    {
        $query = $this->getModel()->newQuery();
        $query
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->Microdegrees()
            ->latest('created_at')
            ->with(
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                SelectColumnTrait::$reviewsColumnsInline
            )
            ->limit($limit);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function getMicrodegreesV2(Request $request, array $with = []): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->active()
            ->select(SelectColumnTrait::$coursesColumns)
            ->Microdegrees()
            ->with(
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            )
            ->latest('created_at');

        $query = $query->when($request->keyword, function ($query) use ($request) {
            return $query->where('name', 'like', "%{$request->keyword}%");
        });

        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function getRecentlyAddedCourses(int $limit = 30, array $with = []): Collection
    {
        $query = $this->getModel()->newQuery();
        $query
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->latest('created_at')
            ->with(
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                SelectColumnTrait::$reviewsColumnsInline
            )
            ->limit($limit);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function getMostPopularCourses(int $limit = 30, array $with = []): Collection
    {
        //TODO: depend on number of enrollments
        $query = $this->getModel()->newQuery();
        $query
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->with(
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            )
            ->latest('created_at')
            ->limit($limit);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function getComingSoonCourses(int $limit = 30, array $with = [], ?User $user = null): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->Draft()
            ->select(SelectColumnTrait::$coursesColumns)
            ->course()
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                'category.' . SelectColumnTrait::$imageColumnsInline,
                'sub.' . SelectColumnTrait::$imageColumnsInline,
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->latest('created_at')
            ->limit($limit);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function getRecommendedCourses(?User $user = null, int $limit = 3)
    {
        if (empty($user)) return collect([]);
        $categories = !empty($user->categories) ? $user->categories->pluck('id')->toArray() : [];
        $tags = !empty($user->tags) ? $user->tags->pluck('id')->toArray() : [];

        $query = $this->getModel()->newQuery();
        return $query
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->where(function ($query) use ($tags, $categories) {
                $query->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('course_tags.id', $tags);
                })->orWhereIn('course_category_id', $categories);
            })
            ->where('level', $user->level_experience)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                'category.' . SelectColumnTrait::$imageColumnsInline,
                'sub.' . SelectColumnTrait::$imageColumnsInline,
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function courseFiltration(Request $request, int $perPage = 20)
    {
        $search = $this->getModel()->newQuery();
        $search = $search
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline
            ]);

        $search = $search->when($request->search, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->where('name', "LIKE", "%{$request->search}%")
                    ->orWhere('brief', "LIKE", "%{$request->search}%")
                    ->orWhere('description', "LIKE", "%{$request->search}%")
                    ->orWhere('learn', "LIKE", "%{$request->search}%")
                    ->orWhereHas('tags', function ($query) use ($request) {
                        return $query->where('course_tags.name', "LIKE", "%{$request->search}%");
                    });
            });
        });

        $search = $search->when($request->course_category_id, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->whereIn('course_category_id', $request->course_category_id);
            });
        });

        $search = $search->when($request->level, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->whereIn('level', $request->level);
            });
        });

        $search = $search->when($request->has('start_timing') && $request->has('end_timing'), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query->whereBetween('timing', [(int) $request->start_timing, (int) $request->end_timing]);
            });
        });

        /** sortings
         * Best match [by tags] -> best_match
         * duration longest to short, short to longest -> timing
         * new arrived -> created_at
         */
        $search = $search->when($request->order_by && $request->order_direction, function ($query) use ($request) {
            if ($request->order_by == 'best_match') {
                if ($request->user('api')) {
                    $query->withCount([
                        'tags' => function ($query) use ($request) {
                            $user_tags = $request->user('api')->tags->pluck('id')->toArray();
                            $query->whereIn('course_tags.id', $user_tags);
                        }
                    ]);
                }
            } else {
                return $query->orderBy($request->order_by, $request->order_direction);
            }
        });

        /* $search->dd();*/
        return $search->paginate($perPage);
    }

    public function courseFiltrationV2(Request $request, int $perPage = 18, ?User $user = null)
    {

        $search = $this->getModel()->newQuery()
            ->select(SelectColumnTrait::$coursesColumns)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                //                SelectColumnTrait::$reviewsColumnsInline,
                'category.' . SelectColumnTrait::$imageColumnsInline,
                'sub.' . SelectColumnTrait::$imageColumnsInline,
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            });

        $search = $search->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline
            ]);

        $search = $search->where(function ($query) use ($request) {
            $course_category_id = is_array($request->course_category_id) ? $request->course_category_id : [$request->course_category_id];

            $course_category_id = array_filter($course_category_id, function ($item) {
                return !empty($item);
            });

            $query->when(!empty($request->keyword), function ($qq) use ($request) {

                return $qq->where(function ($query) use ($request) {
                    return $query->where('courses.name', "LIKE", "%{$request->keyword}%")
                        ->orWhere('courses.brief', "LIKE", "%{$request->keyword}%")
                        ->orWhere('courses.description', "LIKE", "%{$request->keyword}%")
                        ->orWhereHas('tags', function ($query) use ($request) {
                            return $query->where('course_tags.name', "LIKE", "%{$request->keyword}%");
                        })
                        ->orWhereHas('chapters', function ($query) use ($request) {
                            return $query->where('chapters.name', 'LIKE', "%{$request->keyword}%");
                        });
                });
            })->when(!empty($course_category_id), function ($query) use ($course_category_id) {
                return $query->whereIn('courses.course_sub_category_id', $course_category_id);
            });
        });

        if ($request->has('level') && $request->level != '') {
            if ($request->level && !is_array($request->level)) $request->level = [$request->level];

            $search = $search->when(!empty($request->level), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    return $query->whereIn('courses.level', $request->level);
                });
            });
        }

        $search = $search->when($request->has('start_timing') && $request->has('end_timing'), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query->whereBetween('courses.timing', [(int) $request->start_timing * 60, (int) $request->end_timing * 60]);
            });
        });

        if ($request->has('job_role_id') && $request->job_role_id != '') {
            $request->job_role_id = is_array($request->job_role_id) ? $request->job_role_id : [$request->job_role_id];
            $search = $search->when(!empty($request->has('job_role_id')), function ($query) use ($request) {

                return $query->whereHas('jobRoles', function ($q) use ($request) {
                    return $q->whereIn('job_roles.id', $request->job_role_id);
                });
            });
        }

        if ($request->has('specialty_area_id') && $request->specialty_area_id != '') {

            $request->specialty_area_id = is_array($request->specialty_area_id) ? $request->specialty_area_id : [$request->specialty_area_id];
            $search = $search->when(!empty($request->has('specialty_area_id')), function ($query) use ($request) {
                return $query->whereHas('specialtyAreas', function ($q) use ($request) {
                    return $q->whereIn('specialty_areas.id', $request->specialty_area_id);
                });
            });
        }
        /** sortings
         * Best match [by tags] -> best_match
         * duration longest to short, short to longest -> timing
         * new arrived -> created_at
         **/
        $request->order_by = 'courses.created_at';
        $request->order_direction = 'desc';

        $search = $search->when($request->order_by && $request->order_direction, function ($query) use ($request) {
            if ($request->order_by == 'best_match') {
                if ($request->user('api')) {
                    $query->withCount([
                        'tags' => function ($query) use ($request) {
                            $user_tags = $request->user('api')->tags->pluck('id')->toArray();
                            $query->whereIn('course_tags.id', $user_tags);
                        }
                    ]);
                }
            } else {
                return $query->orderBy($request->order_by, $request->order_direction);
            }
        });

        /* $search->dd();*/

        return $search->paginate($perPage);
    }

    public function courseFiltrationLearnPath(Request $request, int $perPage = 20)
    {
        $search = $this->getModel()->newQuery();
        $search = $search->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline
            ]);

        $search = $search->when($request->search, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->where('name', "LIKE", "%{$request->search}%")
                    ->orWhere('brief', "LIKE", "%{$request->search}%")
                    ->orWhere('description', "LIKE", "%{$request->search}%");
            });
        });

        $search = $search->when($request->course_category_id, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query->where('course_category_id', $request->course_category_id);
                $query = $query->orWhere('courses.course_sub_category_id', $request->course_category_id);
                return $query;
            });
        });

        $search = $search->when($request->level, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->where('level', $request->level);
            });
        });

        $search = $search->when($request->has('start_timing') && $request->has('end_timing'), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query->whereBetween('timing', [(int) $request->start_timing, (int) $request->end_timing]);
            });
        });

        $search = $search->when($request->tag, function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                return $query->whereHas('tags', function ($query) use ($request) {
                    return $query->where('course_tags.name', "LIKE", "%{$request->tag}%");
                });
            });
        });

        /** sortings
         * Best match [by tags] -> best_match
         * duration longest to short, short to longest -> timing
         * new arrived -> created_at
         */
        $search = $search->when($request->order_by && $request->order_direction, function ($query) use ($request) {
            if ($request->order_by == 'best_match') {
                if ($request->user('api')) {
                    $query->withCount([
                        'tags' => function ($query) use ($request) {
                            $user_tags = $request->user('api')->tags->pluck('id')->toArray();
                            $query->whereIn('course_tags.id', $user_tags);
                        }
                    ]);
                }
            } else {
                return $query->orderBy($request->order_by, $request->order_direction);
            }
        });

        /* $search->dd();*/
        return $search->paginate($perPage);
    }

    /**
     * @param string $course_category_id
     * @param null   $ignore_id
     * @param int    $limit
     * @return mixed
     */
    public function getCoursesByCategoryId(string $course_category_id, $ignore_id = null, int $limit = 3, ?User $user = null)
    {
        $query = $this->getModel()->newQuery()
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->where('course_category_id', $course_category_id)->orWhere('course_sub_category_id', $course_category_id);
        if ($ignore_id) {
            $query = $query->where('courses.id', '!=', $ignore_id);
        }
        $query = $query->limit($limit)
            ->select(SelectColumnTrait::$coursesColumns)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                'category.' . SelectColumnTrait::$imageColumnsInline,
                'sub.' . SelectColumnTrait::$imageColumnsInline,
                'tags:id,name',
                /*'chapters' => function ($query) {
                    return $query->active()
                        ->with('lessons', function ($query) {
                            return $query->active()->with('chapter', 'course');
                        });
                }*/
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->get();

        return $query;
    }

    public function recommendCoursesDependingSubscriptions(User $user, $limit_free_courses = 8)
    {

        //        header('Access-Control-Allow-Credentials: true'); // for debug purpose [solve cors issue]

        $courses = collect([]);
        $has_pro_access = $user->hasActiveSubscription(AccessType::PRO);
        $has_bundle_courses = $user->hasActiveSubscription(AccessType::COURSES);
        $has_bundle_categories = $user->hasActiveSubscription(AccessType::COURSE_CATEGORY);

        if ($has_pro_access) {
            $courses = $this->getModel()->newQuery()
                ->select(SelectColumnTrait::$coursesColumns)
                ->active()
                ->with([
                    SelectColumnTrait::$imageColumnsInline,
                    SelectColumnTrait::$categoryColumnsInline,
                    SelectColumnTrait::$coverColumnsInline
                ])
                ->latest()
                ->paginate(15);
        } else {
            $courses = $this->getModel()->newQuery()
                ->select(SelectColumnTrait::$coursesColumns)
                ->active()
                ->with([
                    SelectColumnTrait::$imageColumnsInline,
                    SelectColumnTrait::$categoryColumnsInline,
                    SelectColumnTrait::$coverColumnsInline
                ])
                // ->limit($limit_free_courses)
                ->latest()
                ->where('is_free', 1)
                ->paginate(15);
        }

        return $courses;
    }

    public function getFreeCoursesForUnsubscribedUser(User $user)
    {
        $courses = [];
        $has_pro_access = $user->hasActiveSubscription(AccessType::PRO);
        $has_bundle_courses = $user->hasActiveSubscription(AccessType::COURSES);
        $has_bundle_categories = $user->hasActiveSubscription(AccessType::COURSE_CATEGORY);

        if (empty($has_pro_access) && empty($has_bundle_courses) && empty($has_bundle_categories)) {
            $courses = $this->getFreeCourses();
        }
        return $courses;
    }

    public function getCoursesWithLabs(int $limit = 30, array $with = []): Collection
    {
        $query = $this->getModel()->newQuery();
        $query
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()
            ->course()
            ->whereHas('lessons', function ($query) {
                $query->where('type', LessonType::LAB);
            })
            ->with(
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            )
            ->limit($limit)
            ->latest('created_at');
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function best_sellers()
    {
        return $this->getModel()->newQuery()->select(SelectColumnTrait::$coursesColumns)->active()->course()->where('is_best_seller', true)->get();
    }

    public function editorial_picks()
    {
        return $this->getModel()->newQuery()->active()->course()->where('is_editorial_pick', true)->get();
    }

    public function essentials(?User $user = null)
    {
        return $this->getModel()->newQuery()
            ->select(SelectColumnTrait::$coursesColumns)
            ->active()->course()
            ->where('is_essential', true)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->get();
    }

    public function top10(User $user, $random_user_catergory)
    {

        $category_id = $random_user_catergory?->id ?? null;

        $courses =
            $this->getModel()->newQuery()
                ->where('course_sub_category_id', $category_id)
                ->orWhere('course_category_id', $category_id)
                ->take(10)
                ->select(SelectColumnTrait::$coursesColumns)
                ->active()
                ->course()
                ->with([
                    SelectColumnTrait::$imageColumnsInline,
                    SelectColumnTrait::$categoryColumnsInline,
                    SelectColumnTrait::$coverColumnsInline,
                    SelectColumnTrait::$subCategoryColumnsInline,
                ])
                ->when($user, function ($query) use ($user) {
                    $query->with([
                        'completedPercentageLoad' => function ($query) use ($user) {
                            $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                        }]);
                })
                ->latest('courses.created_at')
                ->get();

        return $courses;
    }

    public function top_10_watched()
    {
        return $this->getModel()
            ->selectRaw('courses.*, SUM(watch_history_times.watched_time) AS totalTimes')
            ->where('courses.activation', true)
            ->where('courses.course_type', CourseType::COURSE)
            ->whereRaw('month(courses.created_at) = month(now()) - 1')
            ->join('watch_history_times', 'courses.id', '=', 'watch_history_times.course_id')
            //            ->groupBy('courses.id')
            ->orderBy('totalTimes', 'DESC')
            ->take(10)
            ->get();
    }

    public function hightest_rate(?User $user = null)
    {
        return $this->getModel()
            ->select(
                [
                    DB::raw('courses.*'),
                ]
            )
            ->where('courses.activation', true)
            ->where('courses.course_type', CourseType::COURSE)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->havingRaw('agg_avg_reviews >= 4')
            ->orderBy('agg_avg_reviews', 'DESC')
            ->take(10)
            ->get();
    }

    public function getNotCompletedCourses(User $user): Collection
    {
        $completed_courses = CompletedCourses::where('user_id', $user->id)
            ->latest('created_at')
            ->pluck('course_id');

        $enrolled_courses = $user
            ->course_enrollments()
            ->whereNotIn('courses.id', $completed_courses)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                'category.' . SelectColumnTrait::$imageColumnsInline,
                'sub.' . SelectColumnTrait::$imageColumnsInline,
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->get();

        return $enrolled_courses;
    }

    public function getCompletedCourses(User $user): Collection
    {
        $completed_courses = CompletedCourses::where('user_id', $user->id)
            ->latest('created_at')
            ->pluck('course_id');

        $courses = $user
            ->course_enrollments()
            ->WhereIn('courses.id', $completed_courses)
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
                SelectColumnTrait::$coverColumnsInline,
                SelectColumnTrait::$subCategoryColumnsInline,
                'category.' . SelectColumnTrait::$imageColumnsInline,
                'sub.' . SelectColumnTrait::$imageColumnsInline,
            ])
            ->when($user, function ($query) use ($user) {
                $query->with([
                    'completedPercentageLoad' => function ($query) use ($user) {
                        $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                    }]);
            })
            ->get();

        return $courses;
    }

    public function courseMaxTime()
    {
        return $this->getModel()->max('timing');
    }
}
