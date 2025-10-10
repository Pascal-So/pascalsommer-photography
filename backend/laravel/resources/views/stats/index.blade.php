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

<h2>Hotlink syntax</h2>
<table class="inline-block">
    <tr>
        <td style="text-align: right">
            Photos:
        </td>
        <td>&nbsp;&nbsp;</td>
        <td style="text-align: right">
            #photo812#
        </td>
    </tr>
    <tr>
        <td style="text-align: right">
            Posts:
        </td>
        <td>&nbsp;&nbsp;</td>
        <td style="text-align: right">
            #post32#
        </td>
    </tr>
    <tr>
        <td style="text-align: right">
            Coords:
        </td>
        <td>&nbsp;&nbsp;</td>
        <td style="text-align: right">
            #coords46°27'58.8"N 13°17'37.4"E#
        </td>
    </tr>
</table>

<h2>Debug Tools</h2>

<form action="{{ route('generateThumbnails') }}" method="POST">
    @csrf

    <button class="btn">Generate Thumbnails</button>
</form>

@endsection
