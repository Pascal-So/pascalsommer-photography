@extends('layouts.pascal')

@section('content')

<br><br>
<a class="stealth-link" href="{{ route('home') }}" data-shortcutkeycode="72"><h1>Pascal Sommer</h1></a>

@include('layouts.social-links')

<br><br>

<div class="centered-text">
    <h2>404</h2>

    <p>There is nothing here.</p>
    <a href="{{ route('home') }}">Home</a>

    <br>
    <br>
    <br>
</div>


<style type="text/css">
.centered-text {
    max-width: 450px;
    margin: auto;
}
</style>

@endsection
