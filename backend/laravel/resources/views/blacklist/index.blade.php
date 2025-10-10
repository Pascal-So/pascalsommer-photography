@extends('layouts.pascal')

@section('content')

<h1>Blacklist</h1>

<ul>
    @foreach($entries as $entry)
        <li>{{ $entry->regex }}</li>
    @endforeach
</ul>

@endsection
