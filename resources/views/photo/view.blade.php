@extends('layouts.pascal')

@section('content')

<h2>
    @if($photo->isPublic() )
        {{ $photo->post->formattitle() }}
    @else
        Unpublished Photo
    @endif
</h2>

<div class="flex-center-row">
    @if($photo->prevPhoto() == null)
        <div class="arrow-icon-placeholder"></div>
    @else
        <a href="{{ route('viewPhoto', ['photo' => $photo->prevPhoto()]) }}"><img class="arrow-icon" src="{{ asset('img/icons/larrow.svg') }}"></a>
    @endif
    <img id="photo" class="photo-large" src="{{ asset($photo->path) }}" alt="{{ $photo->alttext() }}">
    @if($photo->nextPhoto() == null)
        <div class="arrow-icon-placeholder"></div>
    @else
        <a href="{{ route('viewPhoto', ['photo' => $photo->nextPhoto()]) }}"><img class="arrow-icon" src="{{ asset('img/icons/rarrow.svg') }}"></a>
    @endif
</div>

<div class="hidden" id="photo_width">{{$photo->width()}}</div>
<div class="hidden" id="photo_height">{{$photo->height()}}</div>

<script type="text/javascript" src="{{ asset('js/setPhotoDimensions.js') }}" async></script>

<br>

<span>Tags:</span>
@foreach($photo->tags as $tag)
    <a class="tag" href="{{ route('filtered', ['tags' => $tag->name]) }}">{{ $tag->name }}</a>
@endforeach

<p>{!! $photo->descriptionHTML() !!}</p>

<br><br>

<a class="btn" href="{{ route('home') }}?page={{ $photo->getPaginationPage() }}#photo_{{ $photo->id }}" title="Home">
    Return to overview
</a>
@auth
    <a class="btn" href="{{ route('editPhoto', compact('photo')) }}">
        Edit Photo
    </a>
@endauth


<br><br>


@include('comment.list', ['comments' => $photo->comments->sortByDesc('created_at')])

<br>
<br>

@if($photo->isPublic())
    @include('comment.form')
@endif

@endsection
