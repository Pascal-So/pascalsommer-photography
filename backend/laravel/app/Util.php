<?php

namespace App;

class Util
{
    /**
     * Escapes MarkdownV2 according to https://core.telegram.org/bots/api#formatting-options
     */
    public static function escapeMarkdown(string $input): string
    {
        $markdown_escape_chars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];

        $output = '';

        $array = str_split($input);
        foreach ($array as $char) {
            if (in_array($char, $markdown_escape_chars)) {
                $output .= "\\$char";
            } else {
                $output .= $char;
            }
        }

        return $output;
    }
}
