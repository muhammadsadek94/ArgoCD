<?php

namespace App\Domains\Enterprise\Features\Api\V1\User\Auth;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Http\Requests\Api\V1\User\CreateUserWithFileRequest;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLearnPaths;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\AssignLearnPathsJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\profile\UpdateUploadStatusJob;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use App\Domains\User\Enum\UserType;
use Constants;
use INTCore\OneARTFoundation\Feature;
use App\Domains\Uploads\Jobs\MarkFileAsUsedJob;
use App\Foundation\Http\Jobs\RespondWithJsonJob;
use App\Foundation\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Enterprise\Jobs\Api\V1\User\Auth\CreateUserWithFileJob;
use App\Domains\User\Http\Resources\Api\V1\User\UserDetailsResource;
use App\Domains\Enterprise\Jobs\Api\V1\User\Device\UpdateDeviceInfoJob;
use App\Domains\Enterprise\Http\Requests\Api\V1\User\RegisterUserRequest;
use App\Domains\Enterprise\Jobs\Api\V1\User\Activation\SendOtpActivationCodeJob;
use App\Domains\Enterprise\Imports\UsersImport;
use App\Domains\Enterprise\Imports\UsersImportValidation;
use App\Domains\User\Models\User;
use Dotenv\Result\Success;
use Maatwebsite\Excel\Facades\Excel;

class CreateUserWithFileFeature extends Feature
{
    public function handle(CreateUserWithFileRequest $request , EnterpriseRepository $enterpriseRepository)
    {
        $admin = auth()->user();
        if ($request->file->getClientOriginalExtension() != 'csv') {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "file",
                    'message' => 'please check the type of the file to be csv !'
                ]
            ]);
        }
//        if ($admin->upload_status){
//            return $this->run(RespondWithJsonErrorJob::class, [
//                'errors' => [
//                    "name" => "Users",
//                    'message' => 'can not upload when other uploading process is running !'
//                ]
//            ]);
//        }
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $path = $request->file->move(public_path('/sheets'), $fileName);
        $array = Excel::toArray(new UsersImportValidation, $path);
        $count = count(collect($array)->first());
        if ($count == 0) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "file",
                    'message' => 'File is empty !'
                ]
            ]);
        }
        if ($count > 50) {
            $this->run(UpdateUploadStatusJob::class, [
                'user' => $admin,
                'upload_status' => false
            ]);
            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "Users",
                    'message' => 'can not add more than 50 user in the same request!'
                ]
            ]);
        }
        $licences = $enterpriseRepository->getLicenses($request, $admin);
        $licences_number = count($licences);


        if ( $count > $licences_number) {
            $this->run(UpdateUploadStatusJob::class, [
                'user' => $admin,
                'upload_status' => false
            ]);


            return $this->run(RespondWithJsonErrorJob::class, [
                'errors' => [
                    "name" => "Licenses",
                    'message' => trans('enterprise::lang.no_licenses')
                ]
            ]);
        }
        Excel::import(new UsersImport($request, $admin ,$enterpriseRepository), $path);

        // List of name of files inside
        $files = glob(public_path('/sheets') . '/*');
        // Deleting all the files in the list
        foreach ($files as $file) {
            if (is_file($file))

                // Delete the given file
                unlink($file);
        }
        $this->run(UpdateUploadStatusJob::class, [
            'user' => $admin,
            'upload_status' => false
        ]);
        return $this->run(RespondWithJsonJob::class, [
            "content" => [
                'message' => 'Success',
            ]
        ]);
    }
}
