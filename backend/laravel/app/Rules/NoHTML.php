<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoHTML implements Rule
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
     * Make sure that the string doesn't contain any HTML. NOTE THAT THIS
     * IS ONLY A BASIC CHECK, NO FULL HTML PARSER!
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $regexes = [
            '/<\s*a\s*href\s*=.*>/',
            '/<\s*script.*>/',
            '/<\s*img\s*src\s*=.*>/',
            '/<\s*style.*>/',
        ];

        foreach ($regexes as $regex) {
            if (preg_match($regex, $value)) {
                return false;
            }
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
        return 'No HTML allowed in :attribute.';
    }
}
