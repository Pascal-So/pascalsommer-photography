<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsRegex implements Rule
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

    public static function isRegex($string): bool
    {
        // roughly from https://gist.github.com/smichaelsen/717fae9055ae83ed8e15
        try {
            return preg_match($string, "") !== false;
        } catch (\Exception $e) {
            return false;
        }
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
        return self::isRegex($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please provide a valid regular expression.';
    }
}
