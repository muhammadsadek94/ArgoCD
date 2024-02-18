<?php

namespace App\Domains\Enterprise\Imports;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Jobs\Api\V1\User\Activation\SendOtpActivationCodeJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\Auth\CreateUserWithFileJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\Auth\RegisterUserJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\Device\UpdateDeviceInfoJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLearnPathsJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLicensesToUsersJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\UpdateUploadStatusJob;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use Constants;
use Maatwebsite\Excel\Concerns\ToArray;
use INTCore\OneARTFoundation\Feature;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use DB;

class UsersImport extends Feature implements ToArray, WithHeadingRow, WithValidation
{

    public $request;
    private $admin;
    private $enterpriseRepository;

    public function __construct($request, $admin, EnterpriseRepository $enterpriseRepository)
    {
        $this->request = $request;
        $this->admin = $admin;
        $this->enterpriseRepository = $enterpriseRepository;
    }

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function array(array $users)
    {
        $this->run(UpdateUploadStatusJob::class, [ // set user upload status true to prevent upload any data while addin those users
            'user' => $this->admin,
            'upload_status' => true
        ]);
        $request = $this->request;
        $license_type = LicneseType::PREMIUM;
        if (isset($request->license_type)) {
            $license_type = $request->license_type;
        }
        $admin = auth()->user();

        $licences = $this->enterpriseRepository->getLicenses($request, $admin);
        $licences_number = count($licences);

        foreach ($users as $i => $user) {
            $request->merge($user);
            if (count($users) <= $licences_number) {

                $user = $this->run(RegisterUserJob::class, [
                    'request' => $request,
                ]);
                if (!isset($user->first_name)) {
                    continue;
                }
                $this->run(AssignLicensesToUsersJob::class, [
                    'license' => $licences[$i],
                    'user' => $user
                ]);

                if (isset($request->learn_paths)) {

                    $this->run(AssignLearnPathsJob::class, [
                        'learnPaths' => $request->learn_paths,
                        'user' => $user
                    ]);
                }
            }

            // send otp
            $this->run(SendOtpActivationCodeJob::class, [
                "user" => $user,
                'auto_activate' => true
            ]);

            // update device token & language
//            $this->run(UpdateDeviceInfoJob::class, [
//                'token' => $user->access_token->token,
//                'device_id' => $request->device_id ?? null,
//                'language' => $request->get('language') ?? $request->header('Accept-Language') ?? Constants::DEFAULT_LANGUAGE
//            ]);
//            DB::commit();
            $this->run(UpdateUploadStatusJob::class, [
                'user' => $admin,
                'upload_status' => false
            ]);

        }

    }


    public function rules(): array
    {

        return [
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'email' => 'required|email|unique:users,email'
        ];
    }
}
