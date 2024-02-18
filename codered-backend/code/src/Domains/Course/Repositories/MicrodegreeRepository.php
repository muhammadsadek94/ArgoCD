<?php

namespace App\Domains\Course\Repositories;

use Illuminate\Http\Request;
use App\Domains\User\Models\User;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\CompletedCourses;
use App\Foundation\Repositories\Repository;
use Framework\Traits\SelectColumnTrait;
use Illuminate\Database\Eloquent\Collection;

class MicrodegreeRepository extends Repository implements MicrodegreeRepositoryInterface
{

    use SelectColumnTrait;

    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function getMicrodegrees(int $limit = 4, array $with = []): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->activeOrHide()
            ->Microdegrees()
            ->latest('created_at')
            ->limit($limit);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }


    public function getMicroDegreeById(string $id)
    {
        $query = $this->getModel()->newQuery();
        $query->activeOrHide()
            ->Microdegrees()
            ->Orwhere('course_type', CourseType::COURSE_CERTIFICATION);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->where(['id' => $id])->orWhere(['slug_url' => $id])->firstOrFail();
    }

    public function getNotEnrolledMicrodegrees(User $user): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->activeOrHide()
            ->Microdegrees()
            ->with('course_enrollments')
            ->whereDoesntHave('course_enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('created_at');
        return $query->get();
    }

    public function getNotCompletedMicrodegrees(User $user): Collection
    {
        $user_microdegrees = $user->microdegree_enrollments()->pluck('courses.id');
        $completed_microdegrees = CompletedCourses::select('id', 'user_id', 'course_id', 'certificate_id', 'degree', 'certificate_number',)
            ->where('user_id', $user->id)
            ->whereIn('course_id', $user_microdegrees)
            ->latest('created_at')
            ->pluck('course_id');

        $enrolled_microdegrees = $user
            ->microdegree_enrollments()
            ->where('expired_at', '>', now())
            ->pluck('course_id');

        $query = $this->getModel()->newQuery();
        $query->select(SelectColumnTrait::$coursesColumns)
            ->activeOrHide()
            ->Microdegrees()
            ->when($user, function ($query) use ($user) {
                $query->with(['completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                }])
                    ->withCount(['lessons' => function ($query) {
                        $query->where('activation', 1)->whereHas('chapter', function ($query) {
                            $query->where('activation', 1);
                        });
                    }]);
            })
            ->with('microdegree:id,course_id,estimated_time')
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
            ])
            ->with(['lessons' => function ($query) {
                $query->active()
                    ->select('id', 'course_id', 'name', 'time', 'type', 'is_free', 'activation');
            }])
            ->where(function ($query) use ($completed_microdegrees, $enrolled_microdegrees) {
                $query->whereNotIn('id', $completed_microdegrees)
                    ->whereIn('id', $enrolled_microdegrees);
            });

        return $query->get();
    }


    public function getCompletedMicrodegrees(User $user): Collection
    {
        $user_microdegrees = $user->microdegree_enrollments()->pluck('courses.id');
        $completed_microdegrees = CompletedCourses::where('user_id', $user->id)
            ->whereIn('course_id', $user_microdegrees)
            ->latest('created_at')
            ->pluck('course_id');

        $query = $this->getModel()->newQuery();
        $query->select(SelectColumnTrait::$coursesColumns)
            ->activeOrHide()
            ->Microdegrees()
            ->when($user, function ($query) use ($user) {
                $query->with(['completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                }])
                    ->withCount(['lessons' => function ($query) {
                        $query->where('activation', 1)->whereHas('chapter', function ($query) {
                            $query->where('activation', 1);
                        });
                    }]);
            })
            ->with('microdegree:id,course_id,estimated_time')
            ->with([
                SelectColumnTrait::$imageColumnsInline,
                SelectColumnTrait::$categoryColumnsInline,
            ])
            ->with(['lessons' => function ($query) {
                $query->active()
                    ->select('id', 'course_id', 'name', 'time', 'type', 'is_free', 'activation');
            }])
            ->whereIn('id', $completed_microdegrees);
        return $query->get();
    }
}
