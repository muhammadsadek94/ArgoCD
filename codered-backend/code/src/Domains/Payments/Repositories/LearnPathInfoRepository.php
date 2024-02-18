<?php

namespace App\Domains\Payments\Repositories;

use App\Domains\Bundles\Enum\DisplayStatus;
use App\Domains\Course\Enum\CourseActivationStatus;
use App\Domains\Course\Jobs\Api\V1\User\GenerateCertificateJob;
use App\Domains\Course\Jobs\Api\V1\User\GenerateLearnPathCertificateJob;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\LearnPathCertificate;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\LearnPathType;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Domains\User\Models\User;
use App\Foundation\Repositories\Repository;
use Request;
use DB;
use Log;

class LearnPathInfoRepository extends Repository implements LearnPathInfoRepositoryInterface
{
    public function __construct(LearnPathInfo $model)
    {
        parent::__construct($model);
    }

    public function filtration($request, $user_id = -null)
    {

        $search = $this->getModel()->newQuery()->with([
            'courses'      => function ($query) {
                $query
                    ->select('course_id', 'learn_path_id')
                    ->whereHas('course');
            },
            'courses_load' => function ($query) {
                $query->select('courses.id', 'name', 'brief', 'level', 'timing', 'course_sub_category_id', 'course_category_id', 'course_type', 'image_id', 'slug_url', 'is_free', 'price', 'discount_price')
                    ->where('courses.activation', CourseActivationStatus::ACTIVE)
                    ->with('image:id,path,full_url,mime_type');
            },

        ])->when(!empty($user_id), function ($query) use ($user_id) {
            return $query->with([
                'allPathCertificates' => function ($query) use ($user_id) {
                    $query->select('learn_path_certificates.id', 'learnpath_id', 'user_id')->where('user_id', $user_id);
                },
                'completedCourses'    => function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                },

            ]);
        });

        $search = $search->active()->paths();

        //category
        if ($request->course_category_id && !is_array($request->course_category_id)) $request->course_category_id = [$request->course_category_id];

        $search = $search->when($request->keyword, function ($query) use ($request) {
            $learnQuery = $query
                ->where('name', 'LIKE', "%{$request->keyword}%")
                ->orWhere('description', 'LIKE', "%{$request->keyword}%")
                ->orWhere('overview', 'LIKE', "%{$request->keyword}%")
                ->orWhereHas('courses', function ($q) use ($request) {
                    return $q->whereHas('course', function ($courseQuery) use ($request) {
                        return $courseQuery->where('name', 'LIKE', "%{$request->keyword}%");
                    });
                });

            return $learnQuery;
        });
        $search = $search->when(!empty($request->course_category_id) && is_array($request->course_category_id), function ($catQuery) use ($request) {
            return $catQuery->whereHas('category', function ($q) use ($request) {
                return $q->whereHas('sub_categories', function ($subQuery) use ($request) {
                    return $subQuery->whereIn('id', $request->course_category_id);
                });
            });
        });

        $search = $search->when(!empty($request->course_category_id) && !is_array($request->course_category_id), function ($catQuery) use ($request) {
            return $catQuery->whereHas('category', function ($q) use ($request) {
                return $q->whereHas('sub_categories', function ($subQuery) use ($request) {
                    return $subQuery->where('id', $request->course_category_id);
                });
            });
        });

        $request->job_role_id = is_array($request->job_role_id) ? $request->job_role_id : [$request->job_role_id];
        $search = $search->when(!empty($request->has('job_role_id')), function ($query) use ($request) {
            return $query->whereHas('jobRoles', function ($q) use ($request) {
                return $q->whereIn('job_roles.id', $request->job_role_id);
            });
        });

        $request->specialty_area_id = is_array($request->specialty_area_id) ? $request->specialty_area_id : [$request->specialty_area_id];
        $search = $search->when(!empty($request->has('specialty_area_id')), function ($query) use ($request) {
            return $query->whereHas('specialtyAreas', function ($q) use ($request) {
                return $q->whereIn('specialty_areas.id', $request->specialty_area_id);
            });
        });

        return $search->paths()->with('image:id,path,full_url,mime_type')->limit(12)->get();
    }

    public function getLearningPathRelatedWithCourses(string $courseId)
    {
        $data = $this
            ->getModel()
            ->newQuery()
            ->active()
            ->with('allPathCertificates:id,user_id,learnpath_id')
            ->when(!empty($courseId), function ($query) use ($courseId) {
                return $query->whereHas('courses', function ($q) use ($courseId) {
                    return $q->whereHas('course', function ($queryCourse) use ($courseId) {
                        return $queryCourse->where('id', $courseId)->with('image:id,path,full_url,mime_type');
                    });
                });
            });
        return $data->with('tools')->first();
    }

    public function getLearnPathsOnly()
    {
        $paths = $this
            ->getModel()
            ->newQuery()
            ->active()
            ->where(function ($query) {
                $query->where('type', '!=', LearnPathType::BUNDLE_CATEGORY)
                    ->where('type', '!=', LearnPathType::BUNDLE_COURSES);
            })
            ->get();

        return $paths;
    }

    public function getBundles($request)
    {
        $query = $this->getModel()->newQuery()->bundles();

        $query = $query->when($request->keyword, function ($query) use ($request) {
            return $query->where('name', 'like', "%{$request->keyword}%")
                ->orWhere('description', "LIKE", "%{$request->keyword}%");
        });

        return $query->latest('created_at')
            ->active()
            ->get();
    }

    public function getUserCurrentCertificate($user_id, $learnpath_id): ?LearnPathCertificate
    {
        return LearnPathCertificate::where(['user_id' => $user_id, 'learnpath_id' => $learnpath_id])->first();
    }

    /**
     * @param     $user_id
     * @param     $course_id
     * @param int $percentage
     * @param int $certificate_number
     * @return CompletedCourses|\Illuminate\Database\Eloquent\Model
     */
    public function createCertificateFile($user_id, $learn_path_id, int $percentage = 0, int $certificate_number = 0)
    {
        $result = $this->getUserCurrentCertificate($user_id, $learn_path_id);
        if ($result) return $result;

        if ($certificate_number == 0) {
            $certificate_number = $this->generateCertificateNumber();
        }

        //$certificate_number = $this->generateCertificateNumber();
        $result = LearnPathCertificate::create([
            'user_id'            => $user_id,
            'learnpath_id'       => $learn_path_id,
            'certificate_id'     => null, // auto generated with job
            'degree'             => 0,
            'certificate_number' => $certificate_number
        ]);

        $user = User::find($user_id);
        dispatch_now(new GenerateLearnPathCertificateJob($user, $result));
    }

    private function generateCertificateNumber()
    {

        try {
            $certificate = DB::table('learn_path_certificates')->select(DB::raw('MAX(CAST(certificate_number AS UNSIGNED)) as max'))->get()[0];
            $last_certificate = $certificate->max;
        } catch (\Exception $exception) {
            Log::error("can not generate certificate number !");
        }

        if ($last_certificate)
            return (int) ($last_certificate) + 1;
        return rand(0, 1000);
    }

    public function getPathWithFilters($request, $type = 'skill', $user_id = null)
    {
        $search = $this->getModel()
            ->newQuery()
            ->select('id', 'name', 'slug_url', 'description', 'features', 'type', 'category_id', 'sub_category_id', 'level', 'agg_courses')
            ->with([
                'courses_load' => function ($query) {
                    $query->select('courses.id', 'timing', 'activation')->where('courses.activation', CourseActivationStatus::ACTIVE);
                },
            ])->when(!empty($user_id), function ($query) use ($user_id) {
                $query->with([
                    'allPathCertificates' => function ($query) use ($user_id) {
                        $query->select('learn_path_certificates.id', 'user_id')->where('user_id', $user_id);
                    },
                    'completedCourses'    => function ($query) use ($user_id) {
                        $query
                            ->select('completed_courses.id', 'user_id', '.completed_courses.course_id')
                            ->where('user_id', $user_id);
                    },
                ]);
            });
        if ($type == 'skill')
            $search = $search->active()->skillPath();
        else
            $search = $search->active()->careerPath();

        if ($request[$type . "_category_id"] && !is_array($request[$type . "_category_id"])) $request[$type . "_category_id"] = [$request[$type . "_category_id"]];
        if ($request[$type . "_job_role_id"] && !is_array($request[$type . "_job_role_id"])) $request[$type . "_job_role_id"] = [$request[$type . "_job_role_id"]];

        $search = $search->when(!empty($request[$type . "_category_id"]), function ($query) use ($request, $type) {
            return $query->whereIn('learn_path_infos.sub_category_id', $request[$type . "_category_id"]);
        });

        $search = $search->when(!empty($request[$type . "_job_role_id"]), function ($query) use ($request, $type) {
            return $query->whereHas('jobRoles', function ($q) use ($request, $type) {
                return $q->whereIn('job_roles.id', $request[$type . "_job_role_id"]);
            });
        });

        $search = $search->when(!empty($request[$type . "_level"]), function ($query) use ($request, $type) {
            return $query->whereIn('learn_path_infos.level', $request[$type . "_level"]);
        });

        $search = $search->when(!empty($request[$type . "_specialty_area_id"]), function ($query) use ($request, $type) {
            return $query->whereHas('specialtyAreas', function ($q) use ($request, $type) {
                return $q->whereIn('specialty_areas.id', $request[$type . "_specialty_area_id"]);
            });
        });
        return $search->paginate(16);
    }
}
