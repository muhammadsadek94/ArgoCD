<?php

namespace App\Domains\Enterprise\Http\Controllers\Admin;

use App\Domains\Enterprise\Repositories\UserEnterpriseRepository;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Payments\Models\PackageSubscription;
use Form;
use Log;
use Mail;
use Constants;
use Carbon\Carbon;
use Swift_TransportException;
use App\Domains\User\Models\User;
use App\Foundation\Rules\NameRule;
use phpDocumentor\Reflection\Type;
use App\Domains\User\Enum\UserType;
use App\Foundation\Enum\ColumnTypes;
use App\Domains\Course\Models\Course;
use App\Domains\Geography\Models\City;
use App\Foundation\Rules\PasswordRule;
use App\Domains\Geography\Models\Country;
use App\Domains\User\Enum\UserActivation;
use App\Domains\Enterprise\Models\License;
use App\Domains\User\Rules\UserPermission;
use App\Domains\User\Models\Lookups\UserTag;
use App\Domains\Enterprise\Models\Enterprise;
use App\Domains\Enterprise\Models\EnterpriseInfo;
use App\Domains\Enterprise\Mails\SendPasswordMail;
use App\Domains\Uploads\Features\UploadFileFeature;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Domains\Enterprise\Rules\EnterprisePermission;
use App\Foundation\Http\Controllers\Admin\CoreController;

class EnterpriseController extends CoreController
{
    protected $upload = Constants::ADMIN_BASE_URL . "/enterprise/action/upload-profile-picture";

    public $domain = "enterprise";
    private UserEnterpriseRepository $user_enterprise_repository;

    public function __construct(User $model, UserEnterpriseRepository $user_enterprise_repository)
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
                'name'         => trans("Status"),
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
                    ]
                ]
            ],

            [
                'name'         => trans("Type"),
                'key'          => 'type',
                'type'         => ColumnTypes::LABEL,
                'label_values' => [
                    UserType::PRO_ENTERPRISE_ADMIN        => [
                        'text'  => trans('enterprise::lang.PRO_ENTERPRISE_ADMIN'),
                        'class' => 'badge badge-success',
                    ], UserType::REGULAR_ENTERPRISE_ADMIN => [
                        'text'  => trans('enterprise::lang.REGULAR_ENTERPRISE_ADMIN'),
                        'class' => 'badge badge-success',
                    ],

                ],

            ],

        ];
        $this->searchColumn = ["first_name", 'last_name', "email", "phone"];        // TODO: relationable search
        $this->isShowable = true;
        $this->permitted_actions = [
            'index'    => EnterprisePermission::ENTERPRISE_INDEX,
            'create'   => EnterprisePermission::ENTERPRISE_CREATE,
            'edit'     => EnterprisePermission::ENTERPRISE_EDIT,
            'show'     => EnterprisePermission::ENTERPRISE_SHOW,
            'delete'   => EnterprisePermission::ENTERPRISE_DELETE,
            'login_as' => EnterprisePermission::LOGGED_AS_ENTERPRISE,
        ];

        $this->user_enterprise_repository = $user_enterprise_repository;
        parent::__construct();
    }

    public function callbackQuery($query)
    {
        return $query->where(function ($query) {
            $query->where('type', UserType::REGULAR_ENTERPRISE_ADMIN)->orWhere('type', '=', UserType::PRO_ENTERPRISE_ADMIN);
        });
    }

    public function onCreate()
    {
        parent::onCreate();

        $this->shareViews();
    }

    public function onEdit()
    {
        parent::onEdit();
        $this->getLicense($this->request->enterprise);
        $this->getLearnPaths($this->request->enterprise);
        $this->getSubAccounts($this->request->enterprise);

        $this->shareViews();
    }

    public function onShow()
    {
        $this->shareViews();
    }

    public function onStore()
    {
        parent::onStore();
        $this->validate(
            $this->request,
            [
                "first_name"            => ["required", "string", "max:50", new NameRule],
                "email"                 => ["required", "unique:users"],
                "password"              => ["required", "min:10", new PasswordRule],
                "type"                  => ["required"],
                "License_number_active" => ["required", 'numeric', 'min:1', 'not_in:0'],
                "subscription"          => ['required_if:type,==,5'],
                "retake_licenses"       => ['required']
            ],
            [
                'subscription.required_if' => 'The subscription field is required.'
            ]
        );
    }

    public function isStored($row)
    {
        // add license
        // $this->createLicense($row, $this->request->License_number_trial, LicneseType::TRIAL); //for trial
        $this->createLicense($row, $this->request->License_number_active, LicneseType::PREMIUM); //for active
        //add learning paths
        $this->addLearnPath($row, $this->request->subscription);
        $this->addNumberOfRetakeLicense($row, $this->request->retake_licenses);

        try {
            $subject = "Your EC-Council Enterprise Account Details";
            $password = $this->request->password;
            $head_message = 'We have updated your EC-Council Learning account as per your recent request.';
            $user_array_info = $row->toArray();

            Mail::to($row->email)->send(new SendPasswordMail($subject, $password, $user_array_info, $head_message));
        } catch (Swift_TransportException $err) {
        }
    }

    public function isUpdated($row)
    {
        // update subAccount type
        $newType = $row->type == UserType::REGULAR_ENTERPRISE_ADMIN ? UserType::REGULAR_ENTERPRISE_SUBACCOUNT : UserType::PRO_ENTERPRISE_SUBACCOUNT;
        User::where('type', '!=', UserType::USER)->where('enterprise_id', $row->id)->update(['type' => $newType]);
        $this->addNumberOfRetakeLicense($row, $this->request->retake_licenses);
    }

    public function onUpdate()
    {
        parent::onUpdate();
        $this->validate($this->request, [
            "first_name"      => ["required", "string", "max:50"],
            "email"           => "required|email|unique:users,email,{$this->request->enterprise}|max:255",
            "type"            => ["required"],
            "subscription.*"  => ['required_if:type,==,5'],
            "retake_licenses" => ['required']

        ]);
    }

    public function postUploadPicture()
    {
        $this->hasPermission(EnterprisePermission::ENTERPRISE_EDIT);

        $this->validate($this->request, [
            'file' => 'required|mimes:jpg,jpeg,png,webp'
        ]);

        return $this->serve(UploadFileFeature::class);
    }

    public function patchUpdatePassword(User $user)
    {
        $this->hasPermission(EnterprisePermission::ENTERPRISE_EDIT);

        $this->validate($this->request, [
            "password" => ["required", "min:6", new PasswordRule]
        ]);

        $subject = "Your EC-Council Enterprise Details";
        $password = $this->request->password;
        $head_message = 'We have updated your EC-Council Learning account as per your recent request.';
        $user_array_info = $user->toArray();

        $user->update(request(['password']));
        foreach ($user->tokens as $token) {
            $token->revoke();
        }
        try {
            Mail::to($user->email)->send(new SendPasswordMail($subject, $password, $user_array_info, $head_message));
        } catch (Swift_TransportException $err) {
        }
        return response()->json([
            'status'  => 'true',
            'message' => $this->successMessage(2, null)
        ]);
    }

    public function UpdateLearnPath(User $user)
    {
        $this->hasPermission(EnterprisePermission::ENTERPRISE_EDIT);

        $this->validate($this->request, [
            "subscription.*" => ['required_if:type,==,5']
        ]);

        $this->addLearnPath($user, $this->request->subscription);

        return response()->json([
            'status'  => 'true',
            'message' => $this->successMessage(2, null)
        ]);
    }

    private function shareViews()
    {
        $package_subscriptions_list = PackageSubscription::
        whereIn('access_type', [AccessType::LEARN_PATH_SKILL, AccessType::LEARN_PATH_CAREER, AccessType::LEARN_PATH_CERTIFICATE, AccessType::PRO])
            //        where('type', '=', SubscriptionPackageType::Enterprise)
            ->where('enterprise_id', null)->active()->pluck('name', 'id');
        $upload = $this->upload;
        if ($package_subscriptions_list) {
            $package_subscriptions_list['all'] = 'Select All';
            $package_subscriptions_list = array_reverse($package_subscriptions_list->toArray());
        }
        $users = $this->user_enterprise_repository->getEnterpriseUsers($this->request, $this->request->enterprise);

        view()->share(compact('package_subscriptions_list', 'upload', 'users'));
    }

    public function getRandomString($length = 27, $prefix = '', $enterpiseName = '')
    {
        $prefix = $prefix . '-' . $enterpiseName . '-';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0 ; $i < $length ; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return "{$prefix}{$randomString}";
    }

    public function createLicense(User $user, $License_number, $type, $subaccount_id = null)
    {
        for ($i = 0 ; $i < $License_number ; $i++) {
            $licence = $this->getRandomString(27, 'EP', $user->first_name);
            $data = [
                'license'       => $licence,
                'expired_at'    => Carbon::now()->addDays(365),
                'duration'      => '365',
                'status'        => LicneseStatusType::PENDING,
                'license_type'  => $type,
                'enterprise_id' => $user->id,
                'subaccount_id' => $subaccount_id,
                'activation'    => true

            ];
            License::create($data);
        }
    }

    public function getLicense($id)
    {
        $enterprise = User::where('id', $id)->first();
        //        dd($enterprise);
        $used_license_trial = License::where('enterprise_id', $id)
            ->where(function ($query) use ($enterprise) {
                $query->whereNotNull('user_id')
                    ->orwhereNotNull('subaccount_id')
                    ->orwhere('used_number', '>', $enterprise->enterpriseInfo ? $enterprise->enterpriseInfo->licenses_reuse_number : 1);
            })
            ->where('license_type', '=', LicneseType::TRIAL)
            ->where('activation', '=', 1)->get();

        $unused_license_trial = License::where('enterprise_id', $id)
            ->whereNull('user_id')
            ->whereNull('subaccount_id')
            ->where('used_number', '<', $enterprise->enterpriseInfo ? $enterprise->enterpriseInfo->licenses_reuse_number : 1)
            ->where('license_type', '=', LicneseType::TRIAL)
            ->where('activation', '=', 1)->get();

        $used_license_active = License::where('enterprise_id', $id)
            ->where(function ($query) use ($enterprise) {
                $query->whereNotNull('user_id')
                    ->orwhereNotNull('subaccount_id')
                    ->orwhere('used_number', '>', $enterprise->enterpriseInfo ? $enterprise->enterpriseInfo->licenses_reuse_number : 1);

            })
            ->where('license_type', '=', LicneseType::PREMIUM)
            ->where('activation', '=', 1)->get();

        $unused_license_active = License::where('enterprise_id', $id)
            ->where('license_type', '=', LicneseType::PREMIUM)
            ->whereNull('user_id')
            ->whereNull('subaccount_id')
            ->where('used_number', '<', $enterprise->enterpriseInfo ? $enterprise->enterpriseInfo->licenses_reuse_number : 1)
            ->where('activation', '=', 1)->get();

        view()->share(compact('used_license_active', 'unused_license_active', 'used_license_trial', 'unused_license_trial'));
    }

    public function updateLicense(User $user)
    {

        $this->validate(
            $this->request,
            [
                "unused_license_trial"  => ['numeric', 'min:0'],
                "unused_license_active" => ['numeric', 'min:0'],
            ]
        );
        $licenseArr = ['unused_license_active' => LicneseType::PREMIUM, 'unused_license_trial' => LicneseType::TRIAL];

        foreach ($licenseArr as $license => $license_type) {
            $current_license_number = $this->request[$license];

            $unused_license = License::where('enterprise_id', $user->id)
                ->where('user_id', '=', null)
                ->where('subaccount_id', '=', null)
                ->where('license_type', '=', $license_type)
                ->where('activation', '=', 1)->get();

            $modified_license_number = (int) $current_license_number - count($unused_license);
            if ($modified_license_number > 0) {
                $this->createLicense($user, $modified_license_number, $license_type);
            } else {
                for ($i = 0 ; $i < abs($modified_license_number) ; $i++) {
                    $unused_license[$i]->activation = 0;
                    $unused_license[$i]->save();
                }
            }
        }
        return response()->json([
            'status'  => 'true',
            'message' => $this->successMessage(2, null)
        ]);
    }

    public function addLearnPath(User $user, $packages)
    {
        if ($packages) {

            foreach ($packages as $package) {
                $data = [
                    'package_id'    => $package,
                    //                'type' => $type,
                    'enterprise_id' => $user->id,
                    'activation'    => true

                ];
                EnterpriseLearnPath::firstOrCreate($data);
            }
        }
    }

    public function getLearnPaths($id)
    {
        $learnPaths = EnterpriseLearnPath::where('enterprise_id', $id)
            ->where('activation', '=', 1)->get();
        view()->share(compact('learnPaths'));
    }

    public function addNumberOfRetakeLicense(User $user, $licenses_reuse_number)
    {
        $enterprise_info = EnterpriseInfo::firstOrCreate(['enterprise_id' => $user->id]);
        $enterprise_info->licenses_reuse_number = $licenses_reuse_number;
        $enterprise_info->save();
    }

    public function getSubAccounts($enterprise_id)
    {
        $subaccounts = $this->user_enterprise_repository->getSubAccountQuery($this->request, $enterprise_id)->get();

        view()->share(compact('subaccounts'));
    }

    public function patchUpdateSubaccountLicenses(User $user)
    {

        $subaccount_id = $this->request->subaccount_id;

        $required_licenses = $this->request->number_licenses;

        $unused_license = License::where('enterprise_id', $user->id)
            ->where('subaccount_id', '=', $subaccount_id)
            ->where('user_id', '=', null)
            ->where('license_type', '=', LicneseType::PREMIUM)
            ->where('activation', '=', 1)->get();

        // req = 10
        // existing // 94
        // $modified_license_number 10 - 94 = -84

        $countUnused = count($unused_license);
        $modified_license_number = (int) $required_licenses - $countUnused;
        if ($modified_license_number > 0) {
            Log::info("increase: modified_license_number: {$modified_license_number} - enterpriseid: {$user->id} - subaccount : {$subaccount_id} - unused: {$countUnused} - required_licenses: {$required_licenses}");
            $this->createLicense($user, $modified_license_number, LicneseType::PREMIUM, $subaccount_id);
        } else { // decrease
            Log::info("decrease: modified_license_number: {$modified_license_number} - enterpriseid: {$user->id} - subaccount : {$subaccount_id} - unused: {$countUnused} - required_licenses: {$required_licenses}");
            for ($i = 0 ; $i < abs($modified_license_number) ; $i++) {
                Log::info("decrease: {$i}");
                $unused_license[$i]->subaccount_id = null;
                $unused_license[$i]->activation = 1;
                $unused_license[$i]->save();
            }
        }
        return response()->json([
            'status'  => 'true',
            'message' => $this->successMessage(2, null)
        ]);
    }

}
