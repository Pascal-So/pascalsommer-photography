<!DOCTYPE html>
<html>
<head>
    @include('layouts.meta')

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/pascal.css') }}">

    <link rel="alternate" type="application/rss+xml" href="{{ asset('feed') }}" title="{{ config('feed.feeds.posts.title') }}">

    <title>@yield('title', 'Pascal Sommer')</title>

    @yield('head')

    @auth
        <script type="text/javascript" src="{{ mix('js/admin.js') }}" defer></script>
    @endauth
</head>
<body>

@include('layouts.admin_nav')

@yield('content-base')

<script type="text/javascript" src="{{ asset('js/keyboardShortcuts.js')}}" defer></script>

</body>
</html>