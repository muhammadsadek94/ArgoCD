<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\SubAccount;

use INTCore\OneARTFoundation\Http\FormRequest;

class  AddNewSubAccountLiceneseRequest extends FormRequest
{

    public function rules()
    {
        return [
            "trialLicenses"         => ["required_if:premiumLicenses,lt,0", "required_if:premiumLicenses,=,", 'numeric' ,'min:0'],
            "premiumLicenses"       => ["required_if:trialLicenses,lt,0", "required_if:trialLicenses,=,", 'numeric','min:0'],
            "subAccount_id"         =>["required", "exists:users,id"],
        ];
    }
}
