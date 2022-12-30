<?php

return [
    'photos_path' => 'img/photos',
    'thumbs_path' => 'img/thumbs',

    'page_description' => 'My personal photography blog',

    // The sum of these two plus the post title length must be shorter
    // than 4096, the max message length for the Telegram API.
    'max_comment_author_length' => 255,
    'max_comment_length' => 2000,
];
