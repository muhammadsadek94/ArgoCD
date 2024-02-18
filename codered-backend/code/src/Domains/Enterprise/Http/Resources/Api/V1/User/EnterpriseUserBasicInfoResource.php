<?php

namespace App\Domains\Enterprise\Http\Resources\Api\V1\User;

use App\Domains\Course\Models\Course;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Payments\Enum\AccessType;
use App\Domains\Payments\Enum\SubscriptionPackageType;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\Enterprise\Models\License;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;
use DB;

class EnterpriseUserBasicInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "first_name" => $this->full_name,
            "email" => $this->email,
            "company_name" => $this->company_name,
            // "image" => new FileResource($this->image),
            "type" => $this->type,

            "join_date" => $this->created_at->format('d M Y'),

            'activation' => $this->activation,
        ];
    }




}
