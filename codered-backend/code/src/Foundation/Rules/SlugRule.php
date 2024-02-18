<?php

namespace App\Foundation\Rules;

use Illuminate\Contracts\Validation\Rule;

class SlugRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool|int
     */
    public function passes($attribute, $value)
    {
        $pattern = '/^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/';
        return preg_match($pattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('The Slug field must be a valid slug include only letters and dashes (-)');
    }
}
