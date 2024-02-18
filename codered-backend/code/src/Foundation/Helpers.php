<?php

use App\Domains\Course\Enum\LessonType;
use App\Domains\Course\Models\Lesson;
use App\Domains\Payments\Enum\AccessPermission;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Course\Enum\CourseType;
use App\Domains\Course\Models\Chapter;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\User;
use Carbon\Carbon;

if (!function_exists('is_true')) {

    /**
     * Checks if a value exists in an array in a case-insensitive manner
     *
     * @param mixed $value
     * @return bool true if needle is found in the array,
     * false otherwise
     */
    function is_true($value)
    {
        if (is_array($value))
            return count($value) > 0;

        return !!$value;
    }
}

if (!function_exists('is_associative_array')) {

    /**
     * Checks if a value exists in an array in a case-insensitive manner
     *
     * @param array $array
     * @return bool true if needle is found in the array,
     * false otherwise
     */
    function is_associative_array(array $array): bool
    {
        return (is_array($array) && !is_numeric(implode("", array_keys($array))));
    }
}

if (!function_exists('has_permission')) {

    /**
     * determind if user has permission to access specific function
     *
     * @param array $ability
     * @return bool
     */
    function has_permission(array $ability): bool
    {
        if (!auth('admin')->user()->can($ability["ability"])) {
            return false;
        }
        return true;
    }
}

if (!function_exists('is_permitted')) {

    /**
     * determind if user has permission to access specific function for blade
     *
     * @param array $ability
     * @return bool true if needle is found in the array,
     * false otherwise
     */
    function is_permitted(array $ability): bool
    {
        if (is_associative_array($ability))
            return has_permission($ability);

        $abilities = $ability;
        foreach ($abilities as $ability) {
            if (has_permission($ability)) return true;
        }
        return false;
    }
}

/**
 * Codered
 */

if (!function_exists('has_access_course')) {

    /**
     * determind if user has access to course
     *
     * @param Course $course
     */
    function has_access_course(Course $course, ?User $user = null)
    {
        /** @var User $user */
        if (!$user)
            $user = auth()->guard('api')->user();

        if ($user) {

            if ($course->is_free == 1) return 1;
            if ($course->course_type == CourseType::COURSE) {
                if ($user->hasActiveSubscription(AccessType::PRO)) {
                    return 1;
                }

                if ($user->allowedToAccessCategory($course->course_category_id)) {
                    return 1;
                }

                if ($user->allowedToAccessCourse($course->id)) {
                    return 1;
                }

                if ($user->allowedToAccessCourseRelatedWithLeanPath($course->id)) {
                    return 1;
                }
            } elseif ($course->course_type == CourseType::MICRODEGREE || $course->course_type == CourseType::COURSE_CERTIFICATION) {
                return $user->microdegree_certifications_enrollments()->where('course_id', $course->id)->count() > 0;
            }
        } else
            return false;
    }

    function lesson_access_permission($lesson)
    {
        /**
         * Lessons Types Group
         */
        $FREE_LESSONS = [
            LessonType::VIDEO,
            LessonType::DOCUMENT,
            LessonType::QUIZ,
        ];

        $PERMISSION_ACCESS_BY_LESSON = [
            LessonType::VIDEO        => [AccessPermission::FULL_CONTENT, AccessPermission::CONTENT_ONLY, AccessPermission::CONTENT_WITH_LABS, AccessPermission::CONTENT_WITH_VOUCHERS],
            LessonType::DOCUMENT     => [AccessPermission::FULL_CONTENT, AccessPermission::CONTENT_ONLY, AccessPermission::CONTENT_WITH_LABS, AccessPermission::CONTENT_WITH_VOUCHERS],
            LessonType::QUIZ         => [AccessPermission::FULL_CONTENT, AccessPermission::CONTENT_ONLY, AccessPermission::CONTENT_WITH_LABS, AccessPermission::CONTENT_WITH_VOUCHERS],
            LessonType::LAB          => [AccessPermission::FULL_CONTENT, AccessPermission::CONTENT_WITH_LABS],
            LessonType::CYPER_Q      => [AccessPermission::FULL_CONTENT, AccessPermission::CONTENT_WITH_LABS],
            LessonType::VOUCHER      => [AccessPermission::FULL_CONTENT, AccessPermission::CONTENT_WITH_VOUCHERS],
            LessonType::PROJECT      => [AccessPermission::FULL_CONTENT],
            LessonType::VITAL_SOURCE => [AccessPermission::FULL_CONTENT],
            LessonType::CHECKPOINT   => [AccessPermission::FULL_CONTENT, AccessPermission::CONTENT_WITH_LABS],
        ];

        /** @var User $user */
        $user = auth()->guard('api')->user();

        if (!$user) return false;

        $courseType = $lesson->course->course_type;

        if ($lesson->course->is_free == 1) return true;

        if ($courseType == CourseType::MICRODEGREE || $courseType == CourseType::COURSE_CERTIFICATION) {
            $enrollment = $user->all_course_enrollments->where('id', $lesson->course_id)->first();
            $enrollmentDate = optional($enrollment)->pivot?->created_at;
            $enrollmentDate = $enrollmentDate ? Carbon::parse($enrollmentDate) : null;
            $targetDate = Carbon::parse("2023-11-10");

            if (
                ($enrollmentDate && $enrollmentDate < $targetDate) &&
                ($courseType == CourseType::MICRODEGREE || $courseType == CourseType::COURSE_CERTIFICATION)
            ) {
                return true;
            }
        }

        //        if ($user->all_course_enrollments->where('id', $lesson->course_id)->first()?
        //->pivot->created_at < "2023-04-21"
        // && ($lesson->course->course_type == CourseType::MICRODEGREE || $lesson->course->course_type == CourseType::COURSE_CERTIFICATION)) return true;

        foreach ($user->active_subscription as $subscription) {

            if (in_array($lesson->type, $FREE_LESSONS)) return true;

            if (in_array($subscription->package?->access_permission, $PERMISSION_ACCESS_BY_LESSON[$lesson->type])) {
                return true;
            }
        }

        return false;
    }

    function check_lesson_drip_time($lesson)
    {
        $user = auth()->guard('api')->user();

        if ($user?->chapters_packages?->count()) {
            $chapter_package = $user->chapters_packages->where('id', $lesson->chapter->id)->first();
            if ($user->course_user_subscription?->status == SubscribeStatus::TRIAL && $chapter_package?->pivot->is_free_trial) return true;
            if ($user->course_user_subscription?->is_installment && $user->paid_installment_count >= $chapter_package?->pivot->after_installment_number) return true;
            if ($user->course_user_subscription?->status == SubscribeStatus::ACTIVE && !$user->course_user_subscription?->is_installment) return true;

            return false;
        }

        $enrollment_time = $user->all_course_enrollments->where('id', $lesson->course_id)->first()->created_at;
        if (empty($enrollment_time)) return false;
        $chapter = $lesson->chapter;
        if (Carbon::now() > $enrollment_time->addDays($chapter->drip_time))
            return true;
        else
            return false;
    }
}

if (!function_exists('get_user_subscription_by_course')) {
    function get_user_subscription_by_course(Course $course, ?User $user = null)
    {
        return $user->active_subscription->filter(function ($subscription) use ($course) {
            if ($subscription->package?->access_id) {
                if (is_string($subscription->package?->access_id)) {
                    return in_array($course->id, json_decode($subscription->package?->access_id, true));
                } else {
                    return in_array($course->id, $subscription->package?->access_id);
                }
            }
        })->first();
    }
}

if (!function_exists('has_access_course_eager')) {

    /**
     * determind if user has access to course
     *
     * @param Course $course
     */
    function has_access_course_eager(Course $course, ?User $user = null)
    {
        /** @var User $user */
        if (!$user)
            $user = auth()->guard('api')->user();

        if ($user) {

            if ($course->is_free == 1) return 1;
            if ($course->course_type == CourseType::COURSE) {
                if (hasActiveSubscription($user, AccessType::PRO)) {
                    return 1;
                }

                if (allowedToAccessCategory($user, $course->course_category_id)) {
                    return 1;
                }

                if (allowedToAccessCourse($user, $course->id)) {
                    return 1;
                }

                if (allowedToAccessCourseRelatedWithLeanPath($user, $course->id)) {
                    return 1;
                }
            } elseif ($course->course_type == CourseType::MICRODEGREE || $course->course_type == CourseType::COURSE_CERTIFICATION) {
                return $user->all_course_enrollments->where('id', $course->id)->where('pivot.expired_at', '>', Carbon::now())->count() > 0;
            }
        } else
            return false;
    }

    function hasActiveSubscription($user, $type)
    {
        return $user->active_subscription->filter(function ($subscription) use ($type) {
                return $subscription->package?->access_type == $type;
            })->count() > 0;
    }

    function allowedToAccessCategory($user, $category_id)
    {
        $active_subscriptions = $user->active_subscription->filter(function ($subscription) use ($category_id) {
            return $subscription->package?->access_type == AccessType::COURSE_CATEGORY;
        });

        $access_ids = $active_subscriptions->map(function ($subscription) {
            if (is_string($subscription->package?->access_id)) {
                return json_decode($subscription->package?->access_id, true);
            } else {
                return collect($subscription->package?->access_id)->map(function ($item) {
                    return $item;
                });
            }
        })->filter()->flatten();

        return $access_ids->contains($category_id);
    }

    function allowedToAccessCourse($user, $course_id)
    {
        $access_types = [AccessType::COURSES, AccessType::INDIVIDUAL_COURSE, AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_CERTIFICATE, AccessType::LEARN_PATH_SKILL];

        $active_subscriptions = $user->active_subscription->filter(function ($subscription) use ($course_id, $access_types) {
            return in_array($subscription->package?->access_type, $access_types);
        });

        $access_ids = $active_subscriptions->map(function ($subscription) {
            if (is_string($subscription->package?->access_id)) {
                return json_decode($subscription->package?->access_id, true);
            } else {
                return collect($subscription->package?->access_id)->map(function ($item) {
                    return $item;
                });
            }
        })->filter()->flatten();

        return $access_ids->contains($course_id);
    }

    function allowedToAccessCourseRelatedWithLeanPath($user, $course_id)
    {
        $access_types = [AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_CERTIFICATE, AccessType::LEARN_PATH_SKILL];

        $active_subscriptions = $user->active_subscription->filter(function ($subscription) use ($course_id, $access_types) {
            return in_array($subscription->package?->access_type, $access_types);
        });

        $access_ids = $active_subscriptions->map(function ($subscription) {
            if (is_string($subscription->package?->access_id)) {
                return json_decode($subscription->package?->access_id, true);
            } else {
                return collect($subscription->package?->access_id)->map(function ($item) {
                    return $item;
                });
            }
        })->filter()->flatten();

        return $access_ids->contains($course_id);
    }
}

if (!function_exists('getSASForBlob')) {

    /**
     * Generate sas token
     *
     * @param        $blob
     * @param null   $expiry
     * @param null   $container
     * @param string $permissions
     * @return string
     */
    function getSASForBlob($blob, $expiry = null, $container = null, $permissions = 'r')
    {

        $accountName = config('filesystems.disks.azure.name');
        $container = $container == null ? config('filesystems.disks.azure.container') : $container;
        $key = config('filesystems.disks.azure.key');
        $expiry = $expiry == null ? now()->addDay()->format('Y-m-d') : $expiry;

        /* Create the signature */
        $_arraysign = [];
        $_arraysign[] = $permissions;
        $_arraysign[] = '';
        $_arraysign[] = $expiry;
        $_arraysign[] = '/' . $accountName . '/' . $container . '/' . $blob;
        $_arraysign[] = '';
        $_arraysign[] = "2014-02-14"; //the API version is now required
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';

        $_str2sign = implode("\n", $_arraysign);

        return base64_encode(
            hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
        );
    }
}

if (!function_exists('getBlobUrl')) {

    /**
     * get full url for file on azure blob
     *
     * @param        $blob
     * @param        $signature
     * @param        $container
     * @param null   $expiry
     * @param string $resourceType
     * @param string $permissions
     * @return string
     */
    function getBlobUrl($blob, $signature, $container, $expiry = null, $resourceType = 'b', $permissions = 'r')
    {
        $accountName = config('filesystems.disks.azure.name');
        $container = $container == null ? config('filesystems.disks.azure.container') : $container;
        $expiry = $expiry == null ? now()->addDay()->format('Y-m-d') : $expiry;

        /* Create the signed query part */
        $_parts = [];
        $_parts[] = (!empty($expiry)) ? 'se=' . urlencode($expiry) : '';
        $_parts[] = 'sr=' . $resourceType;
        $_parts[] = (!empty($permissions)) ? 'sp=' . $permissions : '';
        $_parts[] = 'sig=' . urlencode($signature);
        $_parts[] = 'sv=2014-02-14';

        /* Create the signed blob URL */
        $_url = 'https://'
            . $accountName . '.blob.core.windows.net/'
            . $container . '/'
            . $blob . '?'
            . implode('&', $_parts);

        return $_url;
    }
}

if (!function_exists('getSasBlob')) {

    /**
     * generate sas url
     *
     * @param      $uri
     * @param      $container
     * @param null $expiry
     * @return string
     */
    function getSasBlob($uri, $container, $expiry = null)
    {
        $signature = getSASForBlob($uri, $expiry, $container);

        $blobUrl = getBlobUrl($uri, $signature, $container, $expiry);

        return $blobUrl;
    }
}

if (!function_exists('array_flatten')) {

    /**
     * Convert a multi-dimensional array into a single-dimensional array.
     *
     * @param array $array The multi-dimensional array.
     * @return array
     * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
     */
    function array_flatten($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            } else {
                $result = array_merge($result, [$key => $value]);
            }
        }
        return $result;
    }
}
