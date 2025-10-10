<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $table = 'blacklist';

    protected $guarded = ['id'];

    public static function checkComment(string $comment): bool
    {
        $regexes = self::pluck('regex');

        foreach ($regexes as $regex) {
            if (preg_match($regex, $comment)) {
                return false;
            }
        }
        return true;
    }
}
