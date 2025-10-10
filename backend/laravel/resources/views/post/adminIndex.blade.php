@extends('layouts.pascal')

@section('title', 'Posts - Pascal Sommer')

@section('content')

<h1>All Posts</h1>

@php

@endphp

<ul>
    @foreach($posts as $post)
        <li>
            <h2>{{ $post->formatTitle() }} - {{ $post->photos->count() }} photos</h2>
            <a class="btn" href="{{ $post->url() }}">View Post</a>
            <a class="btn" href="{{ route('editPost', compact('post')) }}">Edit Post</a>
            <a data-deletable-post data-title="{{ str_replace('"', "'", $post->title) }}"
                class="btn" href="{{ route('deletePost', compact('post')) }}">Delete Post</a>
        </li>
    @endforeach
</ul>


@endsection
