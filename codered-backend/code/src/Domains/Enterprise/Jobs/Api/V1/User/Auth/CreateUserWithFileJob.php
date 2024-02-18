<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\Auth;

use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\User\Mails\SendPasswordMail;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\Lookups\UserTag;
use App\Domains\Enterprise\Notifications\SendPasswordNotification;
use Illuminate\Http\Request;
use App\Domains\User\Enum\UserType;
use INTCore\OneARTFoundation\Job;
use App\Domains\User\Models\User;
use App\Domains\User\Models\UserSubscription;
use App\Domains\User\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Mail;
use Str;

class CreateUserWithFileJob extends Job
{
    use Queueable;

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
            if ($this->request->subaccount_id) {
                $data['subaccount_id'] = $this->request->subaccount_id;
            }
        }

        if ($admin->type == UserType::PRO_ENTERPRISE_SUBACCOUNT || $admin->type == UserType::REGULAR_ENTERPRISE_SUBACCOUNT) {
            $data['subaccount_id'] = $admin->id;
            $data['enterprise_id'] = $admin->enterprise_id;
        }


        $password = Str::random(20);
        $data['password'] = $password;
        try {
            $user = User::create($data);
        } catch (\Exception $e) {
            return $e;
        }
        //        $user->fresh();
        $subject = "Your EC-Council Learning Account Details";
        $head_message = 'We have created a new EC-Council Learning account as per your recent request.';
        try {

            Mail::to($this->request->email)->send(new SendPasswordMail($subject, $password, $user, $head_message));
        } catch (\Exception $e) {
            return $e;
        }
        if ($this->request->tags) {
            $tags = $this->getTags($this->request->tags, $admin);
            $user->userTags()->sync($tags);
        }

        //        if ($this->generateToken == true) {
        //            $device_name = $this->request->get('device_name', 'Login via email & password');
        //            $user->access_token = $user->createToken($device_name);
        //        }
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
