{{-- <h1>{{ $post->formatTitle() }}</h1> --}}
@foreach($photos as $photo)
<img src="{{ asset($photo->thumbPath()) }}" alt="{{ $photo->alttext() }}">
<br>
<p>{!! $photo->descriptionHTML() !!}</p>
<br>
@endforeach