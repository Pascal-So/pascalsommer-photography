<!DOCTYPE html>
<html>
<head>
    @include('layouts.meta')

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=PT+Mono&amp;subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pascal.css') }}">

    <link rel="alternate" type="application/rss+xml" href="{{ asset('feed') }}" title="{{ config('feed.feeds.posts.title') }}">

    <title>Pascal Sommer</title>

    @yield('head')

    @auth
        <script type="text/javascript" src="{{ asset('js/admin.js') }}" defer></script>
    @endauth
</head>
<body>

@include('layouts.admin_nav')

@yield('content-base')


</body>
</html>