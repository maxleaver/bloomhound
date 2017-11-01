<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsLessThanValue implements Rule
{
    protected $name;
    protected $valueToCompare;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->valueToCompare = $value;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value <= $this->valueToCompare;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The amount can not be greater than the value of the ' . $this->name . '.';
    }
}
