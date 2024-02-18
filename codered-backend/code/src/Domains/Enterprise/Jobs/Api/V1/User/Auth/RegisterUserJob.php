<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\Auth;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Mails\SendPasswordMail;
use App\Domains\User\Models\Lookups\UserTag;
use Composer\Downloader\TransportException;
use Illuminate\Http\Request;
use App\Domains\User\Enum\UserType;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserRepository;
use Mail;
use mysql_xdevapi\Exception;
use Str;
use Swift_TransportException;


class RegisterUserJob extends Job
{
    protected $request;

    protected $allowedInputs = [
        'first_name', 'last_name', 'password', 'email', 'image_id', 'social_id',
        'social_type', 'oauth2_client_id', 'country_id', 'subaccount_id'
    ];

    /**
     * @var bool
     */
    private $generateToken;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     * @param bool $generateToken
     */
    public function __construct(Request $request, bool $generateToken = true)
    {
        $this->request = $request;
        $this->generateToken = $generateToken;
    }

    /**
     * Execute the job.
     *
     * @param UserRepository $user_repository
     * @return User
     */
    public function handle(UserRepository $user_repository)
    {
        $data = $this->request->only($this->allowedInputs);
        $data['type'] = UserType::USER;
        $admin = auth()->user();

        if ($admin->type == UserType::PRO_ENTERPRISE_ADMIN || $admin->type == UserType::REGULAR_ENTERPRISE_ADMIN) {
            $data['enterprise_id'] = $admin->id;
            $data['source'] = $admin->first_name;
        }

        if ($admin->type == UserType::PRO_ENTERPRISE_SUBACCOUNT || $admin->type == UserType::REGULAR_ENTERPRISE_SUBACCOUNT) {
            if (!($this->request->subaccount_id)) {
                $data['subaccount_id'] = $admin->id;
                $data['enterprise_id'] = $admin->enterprise_id;
            }
            else{
                $data['subaccount_id'] = $this->request->subaccount_id;
                $data['enterprise_id'] = $admin->enterprise_id;
            }
            $data['source'] = $admin->first_name;
        }
        $user = $user_repository->findByMail($this->request->email);
        $password = Str::random(20);
        $data['password'] = $password;
        if (!$user){
            $user = $user_repository->fillAndSave($data);
        }elseif(empty($user->enterprise_id) && empty($user->subaccount_id)){
            $user->update($data);
        }else{
            return null;
        }

        $subject = "Your EC-Council Learning Account Details";
        $head_message = 'We have created a new EC-Council Learning account as per your recent request.';
        try {
            Mail::to($this->request->email)->send(new SendPasswordMail($subject, $password, $user, $head_message));
        }
        catch (Swift_TransportException $err) {
        }
        if ($this->request->tags) {
            $tags = $this->getTags($this->request->tags, $admin);
            $user->userTags()->sync($tags);
        }

//            if($this->request->learn_paths)
//            {
//                $paths = [];
//
//                foreach($admin->learn_paths()->select('package_id')->get() as $enterprise_learn_paths)
//                {
//                    array_push($paths,$enterprise_learn_paths->package_id);
//                }
//
//                foreach($this->request->learn_paths as $learn_path)
//                {
//                    if(in_array($learn_path,$paths)){
//                        $admin->licenses->where('user_id',null)->first()->update(['package_id' => $learn_path , 'user_id' => $user->id, 'expired_at' => '']);
//                    }
//                }
//
////                $licenses =  $user->userLicenses()->get();
////
////                foreach($licenses as $license)
////                {
////                    if($license->license_type == LicneseType::TRIAL){
////                        $status = SubscribeStatus::TRIAL;
////                    }
////                    if($license->license_type == LicneseType::PREMIUM){
////                        $status = SubscribeStatus::ACTIVE;
////                    }
////                    else{
////                        $status = 0;
////                    }
////                    UserSubscription::create(['user_id' => $user->id, 'package_id' => $learn_path, 'status' => $status , 'expired_at' => Carbon::now()->addYear() , 'subscription_id' => $license->license ]);
////                }
//            }

        if ($this->generateToken == true) {
            $device_name = $this->request->get('device_name', 'Login via email & password');
            $user->access_token = $user->createToken($device_name);
        }
        return $user;


    }

    private function getTags($tags, $enterprise)
    {

        foreach ($tags as $index => $tag_id) {
            $tag_object = UserTag::firstOrCreate(['name' => $tag_id, 'enterprise_id' => $enterprise->id]);
            if (gettype($tag_object->id) == "object") { // if there is no record
                $tag_object->name = $tag_id;
                $tag_object->enterprise_id = $enterprise->id;
                $tag_object->type = 1;
                $tag_object->save();
                $tag_object->id = $tag_object->id->toString();
            }
            $tags[$index] = $tag_object->id;
        }
        return $tags;
    }
}
