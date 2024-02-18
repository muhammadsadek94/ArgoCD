<?php

namespace App\Domains\User\Http\Requests\Api\V1\Instructor;

use App\Domains\User\Enum\UserGender;
use INTCore\OneARTFoundation\Http\FormRequest;

class UpdateBankInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //ignore this id while identify unique user
        $user_id = $this->user()->id;

        return [
            "bank_name"         => ['required'],
//            "account_number"    => ['required'],
            "iban"              => ['required'],
            "swift_code"        => ['required'],
            "billing_address"   => ['required'],
            'payee_name' => ['string'],
            'payee_bank_country' => ['string'],
            'payee_branch_name' => ['string'],
            'branch_code' => ['string'],
            'intermediary_bank' => ['string'],
            'routing_number' => ['string'],
            'payee_bank_for_tt' => ['string'],
        ];
    }

}
