<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name='keywords' content='photography, blog, photos, pictures, camera'>
<meta name='description' content='{{ config('constants.page_description') }}'>
<meta name='subject' content='Photography'>
<meta name='copyright' content='Pascal Sommer'>
<meta name='language' content='EN'>
<meta name='robots' content='index,follow'>
<meta name='author' content='Pascal Sommer'>
<meta name='revisit-after' content='7 days'>
<meta name='target' content='all'>
<meta name='HandheldFriendly' content='True'>
<meta name='MobileOptimized' content='320'>
<meta name='medium' content='blog'>

<meta name='theme-color' content='#19191c'>

<meta name="pinterest" content="nopin" />

<meta property="og:title" content="Pascal Sommer Photography">
<meta property="og:description" content="@yield('social_description', config('constants.page_description'))">
<meta property="og:image" content="@yield('social_image', asset('img/pascalsommerphotography.jpg'))">
<meta property="og:url" content="{{ route('home') }}">

<meta name="twitter:title" content="Pascal Sommer Photography">
<meta name="twitter:description" content="@yield('social_description', config('constants.page_description'))">
<meta name="twitter:image" content="@yield('social_image', asset('img/pascalsommerphotography.jpg'))">
<meta name="twitter:card" content="summary_large_image">