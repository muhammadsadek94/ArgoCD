<?php

namespace App\Domains\User\Http\Requests;

use Illuminate\Validation\Rule;
use INTCore\OneARTFoundation\Http\FormRequest;

class UpdateDisplayNameRequest extends FormRequest
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
        return [
            "display_name"=>['required','alpha_num','max:14','unique:users,display_name'],
            "image_id"=>[Rule::exists('uploads','id')]
        ];
    }
}
