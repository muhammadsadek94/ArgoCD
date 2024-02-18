<?php

namespace App\Domains\Enterprise\Http\Requests\Api\V1\User;

use App\Domains\User\Enum\UserGender;
use INTCore\OneARTFoundation\Http\FormRequest;

class UpdateEnterpriseProfileRequest extends FormRequest
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
        return [
            'first_name' => 'required',
//            'email' => 'required|unique:users,email,' . request()->user,
            'email' => 'required|unique:users,email,'. $this->user()->id,

            'subAccount_id' => 'exists:users,id',
        ];
    }

}
