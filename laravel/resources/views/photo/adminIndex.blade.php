@extends('layouts.pascal')

@section('title', 'Staging - Pascal Sommer')

@section('content')

<h1>
    Photos
</h1>

<div class="staging-view-grid">
    <div class="staging-view-controls">
        <h2><small>Upload</small></h2>
        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <input role="button" class="pretty-much-hidden" type="file" name="photos[]" id="photos_input" multiple>
            <label class="btn" for="photos_input">Select photos</label>
            <button class="btn" id="photos_upload_button" disabled>Upload</button>
        </form>
        <p style="word-wrap: break-word;" id="files_selected_text">No files selected.</p>

        <script type="text/javascript">
            var photos_input = document.getElementById('photos_input');
            var photos_upload_button = document.getElementById('photos_upload_button');

            photos_input.addEventListener('change', function(e) {
                var text = "No files selected.";

                if (photos_input.files) {
                    photos_upload_button.removeAttribute("disabled");

                    if (photos_input.files.length > 1) {
                        text = photos_input.files.length.toString() + " files selected";
                    } else if (photos_input.files.length > 0) {
                        text = photos_input.files[0].name;
                    }
                }

                document.getElementById('files_selected_text').innerHTML = text;
            });
        </script>
    </div>

    <div class="staging-view-controls">
        <h2><small>Filter</small></h2>
        <a class="toggle {{ $staged_photos ? 'toggle-active' : ''}}" href="{{ route('photos') . '?published-photos=' . $published_photos . '&staged-photos=' . !$staged_photos . '&no-desc=' . $no_desc }}">Staged</a>
        <br class="hide-below-469">
        <a class="toggle {{ $published_photos ? 'toggle-active' : ''}}" href="{{ route('photos') . '?published-photos=' . !$published_photos . '&staged-photos=' . $staged_photos . '&no-desc=' . $no_desc }}">Published</a>
        <br class="hide-below-469">
        <a class="toggle {{ $no_desc ? 'toggle-active' : ''}}" href="{{ route('photos') . '?published-photos=' . $published_photos . '&staged-photos=' . $staged_photos . '&no-desc=' . !$no_desc }}">Missing Description</a>

        @if($photos->isEmpty())
            <p>No photos.</p>
        @else
            <p>{{ $photos->count() }} photos</p>
        @endif
        <br><br>
    </div>

    @foreach($photos as $photo)
        <a href="{{ asset($photo->imgPath()) }}" target="blank"><img style="vertical-align: top; max-width: 100%" src="{{ asset($photo->thumbPath()) }}"></a>

        <div class="staging-view-photo-controls">
            <div class="flex-row flex-wrap">
                <a class="btn" href="{{ route('editPhoto', compact('photo')) }}">
                    Edit
                </a>

                <a class="btn" href="{{ $photo->url() }}">
                    View
                </a>

                <a data-deletable-photo data-filename="{{ basename($photo->imgPath()) }}"
                    @if( $photo->isPublic() )
                        data-post="{{ str_replace('"', "'", $photo->post->title) }}"
                    @endif
                    class="btn" href="{{ route('deletePhoto', compact('photo')) }}">
                    Delete
                </a>
            </div>

            <div class="flex-row flex-wrap">
                @foreach($photo->tags as $tag)
                    <a class="tag" href="{{ route('filtered', ['tags' => $tag->name]) }}">{{ $tag->name }}</a>
                @endforeach
            </div>
            <p>
                @if($photo->isPublic())
                    public in "{{ $photo->post->title }}"
                @else
                    not public
                @endif
            </p>
            <p>{!! $photo->descriptionHTML() !!}</p>
        </div>
    @endforeach
</div>



@endsection
