@extends('layouts.pascal')

@section('content')

<br><br>
<a class="stealth-link" href="{{ route('home') }}" data-shortcutkeycode="72"><h1>Pascal Sommer</h1></a>

@include('layouts.social-links')

<br><br>

<div class="centered-text">
    <h2>503</h2>

    <p>The website is temporarily down for maintenance. Meanwhile you could check out some of my other projects here: <a href="https://codelis.ch">codelis.ch</a></p>

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
