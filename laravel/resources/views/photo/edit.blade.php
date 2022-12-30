@extends('layouts.pascal')

@section('title', 'Edit Photo ' . $photo->id . ' - Pascal Sommer')

@section('content')

<h1>Edit Photo</h1>

<h2>
    @if( $photo->isPublic() )
        (live in "{{ $photo->post->title }}")
    @endif
</h2>
<a class="btn" href="{{ $photo->url() }}">View Photo</a>
<br>
<br>
<br>
<div class="flex-row flex-center" id="photo">
    @if($photo->prevPhoto() == null)
        <span class="arrow-icon">
            @include('icons.arrow_placeholder')
        </span>
    @else
        <a  id="link-left"
            class="arrow-icon"
            href="{{ route('editPhoto', ['photo' => $photo->prevPhoto()]) }}#photo">
            @include('icons.left')
        </a>
    @endif
    <img src="{{ asset($photo->imgPath()) }}" class="photo" id="photo">
    @if($photo->nextPhoto() == null)
        <span class="arrow-icon">
            @include('icons.arrow_placeholder')
        </span>
    @else
        <a  id="link-right"
            class="arrow-icon"
            href="{{ route('editPhoto', ['photo' => $photo->nextPhoto()]) }}#photo">
            @include('icons.right')
        </a>
    @endif
</div>

<br><br>

<div id="tags" class="flex-row flex-center flex-wrap photo" style="margin: auto;">
@foreach($tags as $tag)
    @if($photo->tags->contains($tag))
        <a class="tag tag-active" href="{{ route('removeTag', compact('photo', 'tag')) }}">{{ $tag->name }}</a>
    @else
        <a class="tag" href="{{ route('addTag', compact('photo', 'tag')) }}">{{ $tag->name }}</a>
    @endif
@endforeach
</div>

<br><br>

<form method="POST" action="{{ route('updatePhoto', compact('photo')) }}">
    {{ csrf_field() }}

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="5" cols="55">{{ $photo->description}}</textarea>

    <br><br>

    <button class="btn">Save Description</button>

</form>

@endsection
