<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoSpaceContaine implements Rule
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
     * @param  mixed  $value
     */
    public function passes($attribute, $value)
    {
        return preg_match('/\s/', $value) ? false : true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'Space can not be allowed in :attribute.';
    }
}
