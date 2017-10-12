<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiresMarkupValue implements Rule
{
    protected $markupId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($markupId)
    {
        $this->markupId = $markupId;
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
        $valueRequired = \App\Markup::find($this->markupId)->allow_entry;

        if ($valueRequired && is_null($value)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A markup value is required';
    }
}
