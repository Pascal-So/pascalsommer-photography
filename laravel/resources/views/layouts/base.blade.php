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
        <script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>
    @endauth

    <!-- Matomo -->
    <script type="text/javascript">
        var _paq = _paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//analytics.pascalsommer.ch/";
            _paq.push(['setTrackerUrl', u+'piwik.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->
</head>
<body>

@include('layouts.admin_nav')

@yield('content-base')


</body>
</html>