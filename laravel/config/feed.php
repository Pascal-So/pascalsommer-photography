<?php

return [

    'feeds' => [
        'posts' => [
            /*
             * Here you can specify which class and method will return
             * the items that should appear in the feed. For example:
             * 'App\Model@getAllFeedItems'
             *
             * You can also pass an argument to that method:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => 'App\Post@getFeedItems',

            'image' => '',

            'format' => 'atom',

            'contentType' => '',

            /*
             * The feed will be available on this url.
             */
            'url' => '/feed',

            'title' => 'Posts on pascalsommer.ch',
        ],
    ],

];
