<?php

namespace App\Foundation\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailRule implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool|int
     */
    public function passes($attribute, $value)
    {
        return preg_match("/^([a-z0-9\+_\-]+)(.\[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value);

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.phone_invalid');
    }
}
