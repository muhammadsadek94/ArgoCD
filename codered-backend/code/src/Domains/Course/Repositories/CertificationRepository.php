<?php

namespace App\Domains\Course\Repositories;

use App\Domains\Course\Models\CompletedCourses;
use App\Domains\User\Models\User;
use App\Domains\Course\Models\Course;
use App\Foundation\Repositories\Repository;
use Framework\Traits\SelectColumnTrait;
use Illuminate\Database\Eloquent\Collection;

class CertificationRepository extends Repository implements CertificationRepositoryInterface
{

    use SelectColumnTrait;

    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function getCertifications(int $limit = 4, array $with = []): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->activeOrHide()
            ->CourseCertification()
            ->withCount(['lessons' => function ($query) {
                $query->where('activation', 1)->whereHas('chapter', function ($query) {
                    $query->where('activation', 1);
                });
            }])
            ->latest('created_at')
            ->limit($limit);
        if (!empty($with)) {
            $query = $query->with($with);
        }
        return $query->get();
    }

    public function getNotEnrolledCertifications(User $user): Collection
    {
        $query = $this->getModel()->newQuery();
        $query->activeOrHide()
            ->CourseCertification()
            ->when($user, function ($query) use ($user) {
                $query->with(['completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                }]);
            })
            ->withCount(['lessons' => function ($query) {
                $query->where('activation', 1)->whereHas('chapter', function ($query) {
                    $query->where('activation', 1);
                });
            }])
            ->with('course_enrollments')
            ->whereDoesntHave('course_enrollments', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('created_at');
        return $query->get();
    }


    public function getNotCompletedCertifications(User $user): Collection
    {
        $user_microdegrees = $user->certifications_enrollments()->pluck('courses.id');
        $completed_certifications = CompletedCourses::where('user_id', $user->id)
            ->whereIn('course_id', $user_microdegrees)
            ->latest('created_at')
            ->pluck('course_id');

        $enrolled_microdegrees = $user
            ->certifications_enrollments()
            ->where('expired_at', '>', now())
            ->pluck('course_id');

        $query = $this->getModel()->newQuery();
        $query->activeOrHide()
            ->CourseCertification()
            ->when($user, function ($query) use ($user) {
                $query->with(['completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                }]);
            })
            ->withCount(['lessons' => function ($query) {
                $query->where('activation', 1)->whereHas('chapter', function ($query) {
                    $query->where('activation', 1);
                });
            }])
            ->where(function ($query) use ($completed_certifications, $enrolled_microdegrees) {
                $query->whereNotIn('id', $completed_certifications)
                    ->whereIn('id', $enrolled_microdegrees);
            });

        return $query->get();
    }


    public function getCompletedCertifications(User $user): Collection
    {
        $user_microdegrees = $user->certifications_enrollments()->pluck('courses.id');
        $completed_certifications = CompletedCourses::where('user_id', $user->id)
            ->whereIn('course_id', $user_microdegrees)
            ->latest('created_at')
            ->pluck('course_id');

        $query = $this->getModel()->newQuery();
        $query->activeOrHide()
            ->CourseCertification()
            ->when($user, function ($query) use ($user) {
                $query->with(['completedPercentageLoad' => function ($query) use ($user) {
                    $query->select(SelectColumnTrait::$completedPercentageLoadColumns)->where('user_id', $user->id);
                }]);
            })
            ->withCount(['lessons' => function ($query) {
                $query->where('activation', 1)->whereHas('chapter', function ($query) {
                    $query->where('activation', 1);
                });
            }])
            ->whereIn('id', $completed_certifications);
        return $query->get();
    }
}
