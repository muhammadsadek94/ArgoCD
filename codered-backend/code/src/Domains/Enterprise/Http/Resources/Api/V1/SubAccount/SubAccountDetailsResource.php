<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\SubAccount;

use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseDetailsResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\EnterpriseLearnPathsResource;
use App\Domains\Enterprise\Http\Resources\Api\V1\User\UserCourseResource;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;
use App\Domains\Partner\Http\Resources\Api\V1\CourseResource;
use INTCore\OneARTFoundation\Http\JsonResource;

class SubAccountDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                        => $this->id,
            "first_name"                => $this->full_name,
            "company_name"              => $this->company_name,
            "email"                     => $this->email,
            "image"                     => new \Illuminate\Http\Resources\Json\JsonResource($this->image),
            "mins_watched"              => $this->watched_lessons_time(),
            "enrollment"                => $this->all_course_enrollments()->count(),
            "enrollment_courses"        =>UserCourseResource::collection($this->all_course_enrollments()->get()),
            "completed_courses"         =>CourseResource::collection($this->completed_courses()->get()),
            "subaccount"                => new EnterpriseDetailsResource( $this->enterpriseSubAccount()->first()),
            "enterprise"                => new EnterpriseDetailsResource( $this->enterpriseAccount()->first()),
            "join_date"                 => $this->created_at->format('d M Y'),
            'gender'                    => $this->gender,
            'country'                   => new CountryResource($this->country),
            'city'                      => new CityResource($this->city),
            'used_trial_Licenses'       => $this->subAccountLicenses()->where('activation',1)->where('user_id','!=',null)->where('license_type',LicneseType::TRIAL)->count(),
            'used_active_Licenses'      => $this->subAccountLicenses()->where('activation',1)->where('user_id','!=',null)->where('license_type',LicneseType::PREMIUM)->count(),
            'unUsed_trial_Licenses'     => $this->subAccountLicenses()->where('activation',1)->where('user_id',null)->where('license_type',LicneseType::TRIAL)->count(),
            'unUsed_active_Licenses'    => $this->subAccountLicenses()->where('activation',1)->where('user_id',null)->where('license_type',LicneseType::PREMIUM)->count(),
            'tags'                      => $this->usertags()->get(),
            'learn_paths'               =>EnterpriseLearnPathsResource::collection( $this->getLearnPaths()),
            'subscription'              => $this->active_subscription,
            'activation'                => $this->activation
        ];    }
    private function getLearnPaths(){
        return  EnterpriseLearnPath::where('enterprise_id', $this->id)->get();

    }

}
