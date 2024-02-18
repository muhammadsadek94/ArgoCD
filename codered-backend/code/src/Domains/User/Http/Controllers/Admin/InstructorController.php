<?php

namespace App\Domains\User\Http\Controllers\Admin;

use App\Domains\User\Notifications\SendPasswordNotification;
use App\Domains\User\Mails\SendInstructorGeneratedPsswdMail;

use App\Domains\User\Rules\InstructorPermission;
use Form;
use App\Domains\User\Models\User;
use App\Foundation\Rules\NameRule;
use App\Foundation\Enum\ColumnTypes;
use App\Foundation\Rules\PasswordRule;
use App\Domains\Geography\Models\City;
use App\Foundation\Rules\PhoneNumberRule;
use App\Domains\Geography\Models\Country;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Rules\UserPermission;
use App\Foundation\Traits\HasAuthorization;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Foundation\Http\Controllers\Admin\CoreController;

use Illuminate\Support\Facades\Mail;

class InstructorController extends CoreController
{

    use HasAuthorization;

    public $domain = "user";

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->select_columns = [
            [
                'name' => trans("user::lang.image"),
                'key'  => 'image',
                'type' => ColumnTypes::IMAGE,
            ],
            [
                'name' => trans("user::lang.first_name"),
                'key'  => 'first_name',
                'type' => ColumnTypes::STRING,
            ],

            [
                'name' => trans("user::lang.email"),
                'key'  => 'email',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name' => trans("user::lang.phone"),
                'key'  => 'phone',
                'type' => ColumnTypes::STRING,
            ],
            [
                'name'         => trans("user::lang.status"),
                'key'          => 'activation',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    UserActivation::ACTIVE           => [
                        'text'  => trans('lang.active'),
                        'class' => 'badge badge-success',
                    ],
                    UserActivation::SUSPEND          => [
                        'text'  => trans('lang.suspended'),
                        'class' => 'badge badge-danger',
                    ],
                    UserActivation::PENDING          => [
                        'text'  => trans('Pending'),
                        'class' => 'badge badge-info',
                    ],
                    UserActivation::COMPLETE_PROFILE => [
                        'text'  => trans('Pending'),
                        'class' => 'badge badge-info',
                    ],
                    UserActivation::WAITING_APPROVAL => [
                        'text'  => trans('Pending for approval'),
                        'class' => 'badge badge-info',
                    ],

                ]
            ],

        ];
        $this->searchColumn = ["first_name", "email", "phone"];        // TODO: relationable search

        $this->permitted_actions = [
            'index'  => InstructorPermission::INSTRUCTOR_INDEX,
            'create' => InstructorPermission::INSTRUCTOR_CREATE,
            'edit'   => InstructorPermission::INSTRUCTOR_EDIT,
            'delete' => InstructorPermission::INSTRUCTOR_DELETE,
            'login_as' => InstructorPermission::LOGGED_AS_INSTRUCTOR,

        ];

        $this->isShowable = true;

        parent::__construct();

    }

    public function callbackQuery($query)
    {
        return $query->Provider();
    }

    public function onCreate()
    {
        parent::onCreate();
        $this->shareViews();

    }

    public function onEdit()
    {
        parent::onEdit();
        $this->shareViews();
    }

    public function onStore()
    {
        parent::onStore();

        $this->validate($this->request, [
            "first_name"            => ["required", "string", "max:50"],
            "phone"                 => ["unique:users", new PhoneNumberRule, 'nullable'],
            "email"                 => ["required", "unique:users"],
            "password"              => ["required", "min:6", new PasswordRule],

        ], [
            'first_name.required' => 'The full name is required.',
            'first_name.string'   => 'The first name may not be greater than 50 characters.',
        ]);
    }

    public function isStored($row)
    {
        $row->instructor_profile()->create($this->request->only([
            'profile_summary', 'facebook_url', 'instagram_url', 'twitter_url',
            'bank_name', 'account_number', 'iban', 'swift_code',
            'commission_percentage', 'billing_address', 'job'
        ]));

        $subject = "You have been added as an instructor on EC-Council Learning!";
        $password = $this->request->password;

//        Mail::to($this->request->email)->send(new SendInstructorGeneratedPsswdMail($subject, $password, $this->request->all()));

    }

    public function onUpdate()
    {
        parent::onUpdate();

        $this->validate($this->request, [
            "first_name"            => ["required", "string", "max:50"],
            "email"                 => "required|email|unique:users,email,{$this->request->instructor}|max:255",
        ], [
            'first_name.required' => 'The full name is required.',
            'first_name.string'   => 'The first name may not be greater than 50 characters.',
        ]);
    }

    public function isUpdated($row)
    {
        $row->instructor_profile->update($this->request->only([
            'profile_summary', 'facebook_url', 'instagram_url', 'twitter_url',
            'bank_name', 'account_number', 'iban', 'swift_code', 'billing_address', 'job'
        ]));
    }

    public function patchUpdatePassword(User $user)
    {
        $this->hasPermission(UserPermission::USER_EDIT);

        $this->validate($this->request, [
            "password" => ["required", "min:6", new PasswordRule]
        ]);

        $user->update(request(['password']));

        return response()->json([
            'status'  => 'true',
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

    public function getCities($country_id)
    {
        $cities_list = City::active()->where('country_id', $country_id)->pluck('name_en', 'id');

        return Form::select('city_id', $cities_list, $this->request->selected_city_id, ['class' => 'form-control select2']);
    }

    private function shareViews()
    {
        $country_lists = Country::active()->pluck('name_en', 'id');
        view()->share(compact('country_lists'));
    }

    public function generateAndSendPassword(User $user)
    {

        $password = uniqid('crd');
        $user->update([
            'password' => $password
        ]);

         $user->notify( new SendPasswordNotification($password));

        return back()->withSuccess('success', "Password generated and sent to {$user->full_name}");
    }

    public function loggedInAsUser(User $user)
    {
        $this->hasPermission(UserPermission::LOGGED_AS_USER);
        $admin = auth()->guard('admin')->user();
        $session = $user->createToken("Admin Session:{$admin->id}");
        $token = $session->accessToken;

        return redirect(config('user.instructor_login_as') . "?code={$token}");

    }

}
