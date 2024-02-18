<?php

namespace App\Domains\Enterprise\Rules;

use App\Domains\Payments\Enum\LearnPathType;
use Illuminate\Contracts\Validation\Rule;

class WeightlRule implements Rule
{
    private $type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        if($this->type == LearnPathType::BUNDLE_CATEGORY) return true;
        
        $sum = collect( $value)->sum();
        if ($sum == 100) return  true;
        else return false ;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('weight Summation must equal 100 ');
    }
}
