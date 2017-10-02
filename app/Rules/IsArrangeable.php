<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsArrangeable implements Rule
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
     * @return bool
     */
    public function passes($attribute, $value)
    {
        switch ($value)
        {
            case 'flowervariety':
            case 'item':
                return true;
            default:
                return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'That ingredient type cannot be part of an arrangement';
    }
}
