@extends('layouts.pascal')

@section('title', 'Tags - Pascal Sommer')

@section('content')

<h1>Tags</h1>

<p>Numbers indicate the amount of photos <br>with that tag. <i>live</i> (+<i>staged</i>)</p>

@if($tags->count())
    <form method="POST" action="{{ route('updateAllTags') }}">
        {{ csrf_field() }}

        <table class="inline-block">
            @foreach($tags as $tag)
                <tr>
                    <td style="text-align: right">
                        <a href="{{ route('filtered', ['tags' => $tag->name]) }}">{{ $tag->nr_published }} (+{{ $tag->nr_staged }})</a>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <input style="vertical-align: baseline" type="text" name="tags[{{$tag->id}}][name]" value="{{ $tag->name }}">
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <a data-deletable-tag data-name="{{ $tag->name }}"
                            data-photos-live="{{ $tag->nr_published }}"
                            data-photos="{{ $tag->nr_total }}"
                            style="vertical-align: baseline"
                            class="btn"
                            href="{{ route('deleteTag', compact('tag')) }}">
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>

        <br><br>

        <button class="btn">Save Changes</button>
    </form>
@else
    <p>No tags</p>
@endif

<br><br>

<h2>New Tag</h2>
<form method="POST" action="{{ route('storeTag') }}">
    {{ csrf_field() }}
    <label for="name">Name</label>
    <input type="text" name="name" id="name">
    <br><br>
    <button class="btn">Add Tag</button>
</form>

@endsection
