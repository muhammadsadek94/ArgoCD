<?php

namespace App\Domains\Enterprise\Jobs\Api\V1\User\profile;

use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepository;
use App\Domains\Enterprise\Repositories\EnterpriseLearnPathRepositoryInterface;
use App\Domains\Enterprise\Repositories\EnterpriseRepository;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Models\Lookups\UserTag;
use App\Domains\User\Models\User;
use INTCore\OneARTFoundation\Job;

class UpdateProfileJob extends Job
{
    protected $allowedInputs = ["first_name", "last_name", "company_name", 'image_id', 'email', 'phone', 'country_id', 'city_id',
        'gender', 'birth_date', 'subaccount_id', 'activation'];

    public $request;
    public $user;
    public $enterpriseRepository;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $user
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EnterpriseRepository $enterpriseRepository)
    {
        $this->enterpriseRepository = $enterpriseRepository;
        $admin = auth()->user();
        $user = $this->user;
        $data = $this->request->only($this->allowedInputs);
        if (isset($this->request->activation) && $user->activation != $this->request->activation) {
//            dd($user->activation, $this->request->activation, UserActivation::SUSPEND ,$this->request->activation == UserActivation::SUSPEND);

//            if ($this->request->activation == UserActivation::SUSPEND) {
//                $this->removeLicense();
//            }
//            if ($this->request->activation == UserActivation::ACTIVE) {
////                $licenses = $enterpriseRepository->getLicenses($this->request, auth()->user());
//
//                $licensesAdded = $this->addLicense();
//                if (!$licensesAdded)
//                    return null;
//            }
        }
        $tags = [];
        if ($this->request->tags) {
            $tags = $this->getTags($this->request->tags, $admin);
        }
        $user->userTags()->sync($tags);
        $user->update($data);


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

    private function addLicense()
    {
//        dd(11);
        $licenses = $this->enterpriseRepository->getLicenses($this->request, auth()->user());
//        dd(count($licenses));
        if (count($licenses) > 0) {
            $this->enterpriseRepository->assignLicensesToUser($this->user, $licenses[0]);
            return true;
        } else {
            return false;
        }
    }

    private function removeLicense()
    {
        $this->enterpriseRepository->removeAssignLicensesToUser($this->user);
    }
}
