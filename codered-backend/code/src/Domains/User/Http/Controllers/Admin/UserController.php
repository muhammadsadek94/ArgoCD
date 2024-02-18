<?php

namespace App\Domains\User\Http\Controllers\Admin;

use App\Domains\Course\Enum\CourseType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Course\Jobs\Api\V1\User\EnrollInCourseJob;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Jobs\Api\V1\User\GenerateCertificateJob;
use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Repositories\AssessmentsRepositoryInterface;
use App\Domains\Course\Models\Course;
use App\Domains\Geography\Models\City;
use App\Domains\Geography\Models\Country;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Mails\SendPasswordMail;
use App\Domains\User\Models\Lookups\UserTag;
use App\Domains\User\Models\User;
use App\Domains\User\Rules\UserPermission;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Http\Controllers\Admin\CoreController;
use App\Foundation\Rules\NameRule;
use App\Foundation\Rules\PasswordRule;
use App\Foundation\Rules\PhoneNumberRule;
use App\Foundation\Traits\HasAuthorization;
use Form;
use Illuminate\Support\Facades\Mail;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\FinalAssessmentAnswers;
use App\Domains\Course\Models\FinalAssessmentTimer;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use Illuminate\Support\Facades\View;

class UserController extends CoreController
{

    use HasAuthorization;

    public $domain = "user";

    public function __construct(User $model)
    {

        $this->model = $model;
        $this->select_columns = [
            [
                'name' => trans("user::lang.first_name"),
                'key' => 'first_name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("user::lang.last_name"),
                'key' => 'last_name',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("user::lang.email"),
                'key' => 'email',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("Registeration date"),
                'key' => 'created_at',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("user::lang.login"),
                'type' => ColumnTypes::CALLBACK,
                'key' => function ($row) {
                    //Show only login details if through API.Not for users created from backend
                    if ($row->accessTokens->isNotEmpty()) {
                        $last_session = $row->accessTokens()->latest()->first()->created_at->format('Y-m-d h:i');
                        return $last_session;
                    }
                },
            ],

            [
                'name' => trans("Status"),
                'key' => 'activation',
                'type' => ColumnTypes::LABEL,
                'label_values' => [
                    UserActivation::ACTIVE => [
                        'text' => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    UserActivation::SUSPEND => [
                        'text' => trans('lang.suspended'),
                        'class' => 'badge badge-danger',
                    ],
                    UserActivation::PENDING => [
                        'text' => trans('Pending'),
                        'class' => 'badge badge-info',
                    ],
                    UserActivation::COMPLETE_PROFILE => [
                        'text' => trans('Pending'),
                        'class' => 'badge badge-info',
                    ]
                ]
            ],

        ];
        $this->searchColumn = ["first_name", 'last_name', "email", "phone"];        // TODO: relationable search
        $this->isShowable = true;
        $this->permitted_actions = [
            'index' => UserPermission::USER_INDEX,
            'create' => UserPermission::USER_CREATE,
            'edit' => UserPermission::USER_EDIT,
            'show' => UserPermission::USER_SHOW,
            'delete' => UserPermission::USER_DELETE,
            'login_as' => UserPermission::LOGGED_AS_USER,
        ];

        parent::__construct();
    }

    public function onIndex()
    {
        parent::onIndex();

        $this->shareViews();
    }

    public function callbackQuery($query)
    {
        $query = $query->user();

        if ($this->request->country_id) {
            $query = $query->where('country_id', $this->request->country_id);
        }

        if ($this->request->user_tag_id) {
            $query = $query->whereHas('usertags', function ($query) {
                return $query->whereIn('user_user_tag.user_tag_id', $this->request->user_tag_id);
            });
        }

        return $query;
    }

    public function onCreate()
    {
        parent::onCreate();

        $this->shareViews();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->ifMethodExistCallIt('onEdit');
        $row = $this->model->findOrFail($id);
        $main_column = $this->main_column;

        $this->pushBreadcrumb($row->$main_column, null, false);
        $this->pushBreadcrumb(trans('lang.edit'));
        $user = User::find($id);
        $course_enrolments = $user->all_course_enrollments;
        $enrolled_courses_with_percentage = [];

        foreach ($course_enrolments as $course_enrol) {

            $percentage = $course_enrol->completedPercentageDB($user);

            if (is_null($percentage) || empty($percentage)) {
                $percentage = 0;
            }

            $enrolled_courses_with_percentage[] = [
                'course_name' => $course_enrol->name,
                'percentage' => $percentage,
                'created_at' => $course_enrol->created_at

            ];
        }

        return $this->view('edit', [
            'row' => $user,
            'breadcrumb' => $this->breadcrumb,
            'enrolled_courses_with_percentage' => $enrolled_courses_with_percentage,
        ]);
    }

    public function onEdit()
    {
        parent::onEdit();

        $this->shareViews();
    }

    public function onShow()
    {
        $this->shareViews();
    }

    //overriding core-controller show method
    public function show($id)
    {

        $this->ifMethodExistCallIt('onShow');

        $user = User::find($id);
        $course_enrolments = $user->all_course_enrollments;
        $enrolled_courses_with_percentage = [];

        foreach ($course_enrolments as $course_enrol) {

            $percentage = $course_enrol->completedPercentageDB($user);

            if (is_null($percentage) || empty($percentage)) {
                $percentage = 0;
            }

            $enrolled_courses_with_percentage[] = [
                'course_name' => $course_enrol->name,
                'percentage' => $percentage,
                'created_at' => $course_enrol->created_at

            ];
        }

        return $this->view('show', [
            'row' => $user,
            'breadcrumb' => $this->breadcrumb,
            'enrolled_courses_with_percentage' => $enrolled_courses_with_percentage,
        ]);
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "first_name" => ["required", "string", "max:50", new NameRule],
            'last_name' => 'string|max:50|nullable',
            "phone" => ["unique:users", new PhoneNumberRule, 'between:6,18'],
            "email" => ["required", "unique:users"],
            "password" => ["required", "min:10", new PasswordRule]
        ]);
    }

    public function isStored($row)
    {
        $row->usertags()->sync($this->request->user_tag_id);

        //Sending an email with credentials
        $subject = "Your EC-Council Learning Account Details";
        $head_message = 'We have created a new EC-Council Learning account as per your recent request.';
        $password = $this->request->password;
        Mail::to($this->request->email)->send(new SendPasswordMail($subject, $password, $this->request->all(), $head_message));
        $this->validate($this->request, [
            "expired_at" => ['after:today'],
            "subscription_id" => ['unique:user_subscriptions,subscription_id'],
        ]);
        // set expiring and accessibility
        if ($this->request->expired_at && $this->request->package_id) {
            $subscription = $row->subscriptions()->create([
                'expired_at' => $this->request->expired_at,
                'status' => $this->request->status ?? SubscribeStatus::TRIAL,
                'package_id' => $this->request->package_id,
                'subscription_id' => empty($this->request->subscription_id) ? uniqid('ADMIN-') : $this->request->subscription_id

            ]);
        }
        if ($this->request->course_id) {
            dispatch_now(new EnrollInCourseJob($row, $this->request->course_id, now()->addYear()));
            //            $row->course_enrollments()->syncWithoutDetaching($this->request->course_id, ['expired_at' => now()->addYear()]);
        }
    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "first_name" => ["required", "string", "max:50", new NameRule],
            /* "last_name"  => ["required", "string", "max:50", new NameRule],*/
            'last_name' => 'string|max:50|nullable',
            "email" => "required|email|unique:users,email,{$this->request->user}|max:255",
        ]);
    }

    public function isUpdated($row)
    {
        $row->usertags()->sync($this->request->user_tag_id);
        if ($row->wasChanged('activation') && $row->activation == UserActivation::SUSPEND) {
            foreach ($row->tokens as $token ){
                $token->revoke();
            }
        }
    }

    public function patchUpdatePassword(User $user)
    {
        $this->hasPermission(UserPermission::USER_EDIT);

        $this->validate($this->request, [
            "password" => ["required", new PasswordRule]
        ]);

        $subject = "Your EC-Council Learning Account Details";
        $password = $this->request->password;
        $head_message = 'We have updated Your EC-Council Learning Account as per your recent request.';
        $user_array_info = $user->toArray();

        Mail::to($user->email)->send(new SendPasswordMail($subject, $password, $user_array_info, $head_message));

        $user->update(request(['password']));
        foreach ($user->tokens as $token) {
            $token->revoke();
        }
        return response()->json([
            'status' => 'true',
            'message' => $this->successMessage(2, null)
        ]);
    }

    public function postUploadPicture()
    {
        $this->hasPermission(UserPermission::USER_EDIT);

        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

    public function patchPhone(User $user)
    {
        $this->hasPermission(UserPermission::USER_EDIT);

        $this->validate($this->request, [
            "phone" => ["required", "unique:users,phone,{$user->id}", new PhoneNumberRule],
        ]);

        $user->update(request(['phone']));

        return response()->json(['status' => 'true', 'message' => $this->successMessage(2, null)]);
    }

    public function addSubscriptionToUser(User $user)
    {
        $this->hasPermission(UserPermission::USER_EDIT);

        $this->validate($this->request, [
            "expired_at" => ["required", 'after:today'],
            "subscription_id" => ['unique:user_subscriptions,subscription_id'],
        ],
            ['expired_at.date_format'=>'Date Is Not Right, Please Check The Date Is Correct']);

        $package = PackageSubscription::find($this->request->package_id);

        $course_ids = (array)(json_decode($package->access_id) ?? []);

//        if (!empty($course_id) && !Course::find($course_id)){
//            return back()->with(['error' => "Course not found"]);
//        }

        $subscription = $user->subscriptions()->create([
            'expired_at' => $this->request->expired_at,
            'status' => $this->request->status ?? SubscribeStatus::TRIAL,
            'package_id' => $this->request->package_id,
            'subscription_id' => empty($this->request->subscription_id) ? uniqid('ADMIN-') : $this->request->subscription_id
        ]);

        foreach ($course_ids ?? [] as $course_id) {
            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $course_id,
                'expired_at' => $this->request->expired_at,
                'user_subscription_id'  => $subscription->id->toString(),
            ]);
        }

        return back()->with(['success' => $this->successMessage(2, null)]);
    }

    public function enrollInMicrodegree(User $user)
    {
        $this->hasPermission(UserPermission::USER_EDIT);

        $this->validate($this->request, [
            "course_id" => ["required"],
            "expired_at" => ["required", 'after:today'],
        ]);

        //        $user->course_enrollments()->syncWithoutDetaching($this->request->course_id, ['expired_at' => now()->addYear()]);

        dispatch_now(new EnrollInCourseJob($user, $this->request->course_id, !empty($this->request->expired_at) ? $this->request->expired_at : now()->addYear()));
        return back()->with(['success' => $this->successMessage(2, null)]);
    }

    public function generateCertificate(User $user, AssessmentsRepositoryInterface $assessments_repository)
    {

        /*Generate a completion certificate for this particular user for the course selected by admin */


        $this->validate($this->request, [
            "certificate_number" => ["unique:completed_courses"],
        ]);

        //If this certificate already exist for the user with same course then delete
        if (CompletedCourses::where('user_id', $user->id)->where('course_id', $this->request->course_id)->exists()) {

            //Delete that record first
            CompletedCourses::where('user_id', $user->id)->where('course_id', $this->request->course_id)->delete();
        }

        //reusing the same code from the assessment repository file
        $assessments_repository->createCertificateFile($user->id, $this->request->course_id, 100, (int)$this->request->get('certificate_number', 0));

        return back()->with(['success' => $this->successMessage(2, null)]);
    }

    public function generateCertificateNunber()
    {
        $certificate_number = CompletedCourses::all()->max('certificate_number');
        return $certificate_number + 1;
    }

    public function getCities($country_id)
    {
        $cities_list = City::active()->where('country_id', $country_id)->pluck('name_en', 'id');

        return Form::select('city_id', $cities_list, $this->request->selected_city_id, ['class' => 'form-control select2']);
    }

    private function shareViews()
    {
        $country_lists = Country::active()->pluck('name_en', 'id');
        $user_tags_lists = UserTag::active()->pluck('name', 'id');
        $micro_degrees = Course::activeOrHide()
            ->where(function ($query) {
                $query->where('course_type', CourseType::MICRODEGREE)
                    ->orWhere('course_type', CourseType::COURSE_CERTIFICATION);
            })
            ->get()
            ->pluck('internal_name_or_name', 'id');

        $package_subscriptions_list = PackageSubscription::where('enterprise_id', null)
            ->pluck('name', 'id');
        $all_courses = Course::get()->pluck('internal_name_or_name', 'id');

        view()->share(compact('country_lists', 'user_tags_lists', 'micro_degrees', 'package_subscriptions_list', 'all_courses'));
    }

    public function loggedInAsUser(User $user)
    {
        $this->hasPermission(UserPermission::LOGGED_AS_USER);
        $admin = auth()->guard('admin')->user();
        $session = $user->createToken("Admin Session:{$admin->id}");
        $token = $session->accessToken;

        return redirect(config('user.user_login_as') . "?code={$token}");
    }

    public function reAssignAssignment($userId, $courseId)
    {
        FinalAssessmentTimer::where(['user_id' => $userId, 'course_id' => $courseId])->delete();
        FinalAssessmentAnswers::where(['user_id' => $userId, 'course_id' => $courseId])->delete();
        return redirect()->back();
    }

    public function deleteSubscription()
    {
        $row  = $this->model->find($this->request->user_id);
        \DB::table('course_enrollment')
            ->where('course_enrollment.user_id', $this->request->user_id)
            ->where('course_enrollment.course_id', $this->request->course)
            ->where('course_enrollment.id', $this->request->enrollment)
            ->delete();
        return redirect()->back();
    }

    public function deleteCertificate()
    {
        CompletedCourses::find($this->request->completed_course)->delete();
        return redirect()->back();
    }
}
