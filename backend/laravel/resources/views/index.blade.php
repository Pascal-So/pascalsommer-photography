@extends('layouts.pascal')

@section('content')

<br><br>
<a class="stealth-link" href="{{ route('home') }}" data-shortcutkeycode="72"><h1>Pascal Sommer</h1></a>

@include('layouts.social-links')

<br><br>

@include('layouts.pagination_nav', ['items' => $posts, 'from_page_two' => $initial_view, 'element_id' => 'start-content'])

@foreach($posts as $post)

    <h2 id="post_{{ $post->titleSlug() }}"><a title="permalink" href="{{ $post->permalink() }}">#</a> {{ $post->formatTitle() }}</h2>

    @foreach($post->photos()->blogOrdered()->get() as $photo)
        <a href="{{ $photo->url() }}" class="photolink">
            <img class="photo"
                src="{{ asset($photo->imgPath()) }}"
                alt="{{ $photo->alttext() }}"
                title="{{ $photo->titletext() }}"
                id="photo_{{ $photo->id }}">
        </a>
        <br>
    @endforeach

@endforeach

@include('layouts.pagination_nav', ['items' => $posts, 'from_page_two' => false])

<br><br><br>

<div>
    <a class="btn" href="{{ route('about') }}">About</a>
    <span>&nbsp;</span>
    <a class="btn" data-shortcutkeycode="70" href="{{ route('filtered') }}#start-content" title="(shortcut: f)">Filter by Tags</a>
    <span>&nbsp;</span>
    <a class="btn" data-shortcutkeycode="82" href="{{ route('randomPhoto') }}" title="Show a random photo (shortcut: r)">
        Random Photo
    </a>
    <span>&nbsp;</span>
    <a class="btn" data-shortcutkeycode="82" href="https://github.com/Pascal-So/pascalsommer-photography" title="Please report problems with this website as GitHub issues">
        GitHub Repository
    </a>
    <span>&nbsp;</span>
    @guest
        <a class="btn" href="{{ route('login') }}">Login</a>
    @endguest
</div>

@endsection
