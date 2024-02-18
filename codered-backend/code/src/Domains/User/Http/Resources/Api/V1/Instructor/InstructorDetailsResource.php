<?php

namespace App\Domains\User\Http\Resources\Api\V1\Instructor;

use App\Domains\Uploads\Http\Resources\FileResource;
use INTCore\OneARTFoundation\Http\JsonResource;
use App\Domains\Geography\Http\Resources\CityResource;
use App\Domains\Geography\Http\Resources\CountryResource;

class InstructorDetailsResource extends JsonResource
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
            "id"                => $this->id,
            "first_name"        => $this->first_name,
            "last_name"         => $this->last_name,
            "type"              => $this->type,
            "has_password"      => $this->has_password,
            "activation_status" => $this->activation_status,
            "access_token"      => $this->when(!is_null($this->access_token), function () {
                return $this->access_token->accessToken;
            }),
            "email"             => $this->email,
            "image"             => new FileResource($this->image),
            "bank_name"         => $this->instructor_profile->bank_name,
            "account_number"    => $this->instructor_profile->account_number,
            "iban"              => $this->instructor_profile->iban,
            "swift_code"        => $this->instructor_profile->swift_code,
            "billing_address"   => $this->instructor_profile->billing_address,
            "payee_name"               => $this->instructor_profile?->payee_name,
            "payee_bank_country"               => $this->instructor_profile?->payee_bank_country,
            "payee_branch_name"               => $this->instructor_profile?->payee_branch_name,
            "branch_code"               => $this->instructor_profile?->branch_code,
            "intermediary_bank"               => $this->instructor_profile?->intermediary_bank,
            "routing_number"               => $this->instructor_profile?->routing_number,
            "payee_bank_for_tt"               => $this->instructor_profile?->payee_bank_for_tt,
        ];
    }
}
