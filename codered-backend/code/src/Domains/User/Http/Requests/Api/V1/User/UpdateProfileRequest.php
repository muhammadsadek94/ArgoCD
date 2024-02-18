<?php

namespace App\Domains\User\Http\Requests\Api\V1\User;

use App\Domains\User\Enum\UserGender;
use INTCore\OneARTFoundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'first_name'=>'required',
            'last_name'=>'required',
            "image_id" => "exists:uploads,id",
            "country_id" => "exists:countries,id",
//            "city_id" => "exists:cities,id",
            'email' => 'required|unique:users,email,'. $this->user()->id,
            'phone' => 'required|unique:users,phone,'. $this->user()->id,
//            'birth_date'=> 'date',
//            'gender' => ['in:' . UserGender::FEMALE . ',' . UserGender::MALE]

        ];
    }

}
