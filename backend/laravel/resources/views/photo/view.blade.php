@extends('layouts.pascal')

@section('social_image', asset($photo->imgPath()))

@section('social_description', $photo->description ?: config('constants.page_description'))

@section('title', 'Photo ' . $photo->id . ' - Pascal Sommer')

@section('content')

@if($photo->isPublic())
    <h2><a title="photo permalink" href="{{ $photo->url() }}">#</a>
        <a class="stealth-link"
        href="{{ $photo->post->permalink() }}">
        {{ $photo->post->formattitle() }}
    </a></h2>
@else
    <h2><a class="stealth-link" href="{{ route('home') }}">
        Unpublished Photo
    </a></h2>
@endif

<div class="flex-row flex-center">
    @if($photo->prevPhoto() == null)
        <span class="arrow-icon">
            @include('icons.arrow_placeholder')
        </span>
    @else
        <a  id="link-left"
            href="{{ $photo->prevPhoto()->url() }}"
            class="arrow-icon"
            data-shortcutkeycode="37"
            title="Show the previous photo (shortcut: left arrow)">
            @include('icons.left')
        </a>
    @endif
    <a href="{{ asset($photo->imgPath()) }}" target="blank">
        <img id="photo" class="photo-large" src="{{ asset($photo->imgPath()) }}" alt="{{ $photo->alttext() }}">
    </a>
    @if($photo->nextPhoto() == null)
        <span class="arrow-icon">
            @include('icons.arrow_placeholder')
        </span>
    @else
        <a  id="link-right"
            href="{{ $photo->nextPhoto()->url() }}"
            class="arrow-icon"
            data-shortcutkeycode="39"
            title="Show the next photo (shortcut: right arrow)">
            @include('icons.right')
        </a>
    @endif
</div>

<div class="hidden" id="photo_width">{{$photo->width()}}</div>
<div class="hidden" id="photo_height">{{$photo->height()}}</div>
<script type="text/javascript" src="{{ asset('js/setPhotoDimensions.js') }}" async></script>

<br>

<div class="flex-row flex-center flex-wrap">
    @foreach($photo->tags as $tag)
        <a class="tag" href="{{ route('filtered', ['tags' => $tag->name]) }}">{{ $tag->name }}</a>
    @endforeach
</div>

<ul>
    @if($photo->description != '')
        <li class="comment">
            <p class="photo-description">Photo {{ $photo->id }}: {!! $photo->descriptionHTML() !!}</p>
        </li>
        <br>
    @endif

    @php
    $comments = $photo->comments->sortBy('created_at');
    @endphp

    @foreach($comments as $comment)
        <li class="comment" id="comment_{{ $comment->id }}">
            <h2>
                <a href="{{ $comment->photo->url() }}#comment_{{ $comment->id }}">
                    {{ $comment->name }}
                </a>
            </h2>

            <p>{{ $comment->created_at->format('Y-m-d') }}</p>
            <p>{!! $comment->commentHTML() !!}</p>

            @auth
                <a href="{{ route('deleteComment', compact('comment')) }}"
                    class="btn btn_comment_delete"
                    data-deletable-comment
                    data-name="{{ str_replace('"', "'", $comment->name) }}"
                    data-comment="{{ str_replace('"', "'", $comment->comment) }}"
                    >
                    Delete
                </a>
            @endauth
        </li>
        <br>
    @endforeach

    @if($photo->isPublic())
        <li class="comment" id="comment_form">
            @include('comment.form')
        </li>
    @endif
</ul>

<a class="btn" data-shortcutkeycode="72" href="{{ route('home') }}?page={{ $photo->getPaginationPage() }}#photo_{{ $photo->id }}" title="Go back to the list of all photos (shortcut: h)">
    Return to Overview
</a>

<a class="btn" data-shortcutkeycode="82" id="link-random" href="{{ route('randomPhoto') }}" title="Show a random photo (shortcut: r)">
    Random Photo
</a>

@auth
    <a class="btn" data-shortcutkeycode="69" href="{{ route('editPhoto', compact('photo')) }}" title="(shortcut: e)">
        Edit Photo
    </a>
@endauth

@php
    $tags_str = implode(',', $photo->tags->pluck('name')->toArray());
    $filter_by_tag_url = route('filtered', ['tags' => $tags_str]);
@endphp
<a  class="btn"
    data-shortcutkeycode="70"
    href="{{ $filter_by_tag_url }}#start-content"
    title="Show all photos that have the same tags as this one does (shortcut: f)">
    Filter by Tags
</a>

<br>

@endsection
