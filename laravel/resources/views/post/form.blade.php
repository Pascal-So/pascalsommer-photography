@php
    $ids_array = old("photos", $post->photos->pluck('id'));

    $post_photos = App\Photo::whereIn('photos.id', $ids_array)->blogOrdered()->get();
    foreach ($post_photos as $photo) {
        $photo->thumb = $photo->thumbPath();
    }
    foreach ($staged as $photo) {
        $photo->thumb = $photo->thumbPath();
    }
@endphp

<div id="post-photos" class="hidden">{{ json_encode($post_photos) }}</div>
<div id="staged-photos" class="hidden">{{ json_encode($staged) }}</div>
<div id="asset-path" class="hidden">{{ asset('') }}</div>
<div id="view-path" class="hidden">{{ str_replace('dummy_photo', '', route('viewPhoto', ['photo' => 'dummy_photo'])) }}</div>

<form method="POST" action="{{ $action }}">
    {{ csrf_field() }}

    @include('layouts.errors')

    <br><br>

    <label for="title">Title </label>
    <input id="title" type="text" name="title" value="{{ old("title", $post->title) }}" required>
    <br><br>
    <label for="date">Date </label>
    <input id="date" type="text" name="date" placeholder="YYYY-MM-DD" value="{{ old("date", $post->date ?: date('Y-m-d')) }}" required>

    <br><br>

    <button class="btn">Save Post</button>

    <br><br><br>

    <div id="photo-selector"></div>
</form>