@extends('layouts.pascal')

@section('title', 'Gallery - Pascal Sommer')

@section('content')

<h1>Gallery</h1>

<br><br><br>

@include('layouts.pagination_nav', ['items' => $photos, 'from_page_two' => true])
<br>
@foreach($photos as $photo)<a href="{{ $photo->url() }}"><img class="photo-gallery" src="{{ asset($photo->thumbPath()) }}"></a>@endforeach

<br>

@include('layouts.pagination_nav', ['items' => $photos])

@endsection
