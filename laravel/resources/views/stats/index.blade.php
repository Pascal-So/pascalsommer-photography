@extends('layouts.pascal')

@section('title', 'Stats - Pascal Sommer')

@section('content')

<h1>Stats</h1>

<table class="inline-block">
    @foreach($stats as $statname => $statval)
        <tr>
            <td style="text-align: right">
                {{ $statname . (trim($statname) !== '' ? ':' : '') }}
            </td>
            <td>&nbsp;&nbsp;</td>
            <td style="text-align: right">
                {{ $statval }}
            </td>
        </tr>
    @endforeach

</table>

<br><br><br>

<p><b>Reference for stuff that I tend to forget</b></p>
<p>Hotlink syntax: #photo812#</p>

<h2>Debug Tools</h2>

<form action="{{ route('generateThumbnails') }}" method="POST">
    @csrf

    <button class="btn">Generate Thumbnails</button>
</form>

@endsection
