<?php

namespace App\Domains\Favourite\Http\Requests\Api\V2\User;

use App\Domains\Favourite\Enum\FavouriteType;
use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed type
 * @property mixed id
 */
class FavouriteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !empty($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validType = array_values(FavouriteType::getConstants());
        $validType = implode(',', $validType);
        return [
            'id' => ['required'],
            'type' => ['required', "in:{$validType}"]
        ];
    }
}
