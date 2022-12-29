@extends('layouts.pascal')

@section('title', 'Search Photos - Pascal Sommer')

@section('content')

<h1>Search Photos</h1>
<a href="{{ route('home') }}">Return to Homepage</a>
<br/>
<br/>
<br/>
<input id="search-field" style="vertical-align: middle" value="{{$terms}}"/><button type="button" style="vertical-align: middle" id="search-button" class="btn">Search</button>
<script defer>
    const btn = document.getElementById('search-button');
    const input = document.getElementById('search-field');

    btn.onclick = (e) => {
        e.preventDefault();
        window.location = `{{ route("search") }}/${input.value}`;
    };
    input.onkeydown = (e) => {
        if (e.keyCode == 13) {
            btn.click();
        }
    }
    window.onkeydown = (e) => {
        if (e.target.tagName == 'BODY' && e.key == '/') {
            e.preventDefault();
            // input.focus();
            input.select();
        }
    }

    @if($terms == "")
        input.focus();
    @endif
</script>
<br><br><br>

@include('layouts.pagination_nav', ['items' => $photos, 'from_page_two' => false, 'element_id' => 'start-content'])
<br>
@foreach($photos as $photo)<a href="{{ $photo->url() }}"><img class="photo-gallery" src="{{ asset($photo->path) }}"></a>@endforeach

<br>

@include('layouts.pagination_nav', ['items' => $photos])

@endsection
