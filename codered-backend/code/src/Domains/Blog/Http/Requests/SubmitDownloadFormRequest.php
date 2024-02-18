<?php

namespace App\Domains\Blog\Http\Requests;

use INTCore\OneARTFoundation\Http\FormRequest;

/**
 * @property mixed type
 * @property mixed email
 */
class SubmitDownloadFormRequest extends FormRequest
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
            'email' => 'required|email',
            'type' => 'required|in:1,2'
        ];
    }
}
