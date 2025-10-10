@extends('layouts.pascal')

@section('title', 'About - Pascal Sommer')

@section('content')

<br><br>
<h1>Pascal Sommer</h1>

@include('layouts.social-links')

<br><br>

<p> Contact me: <a href="mailto:p@pascalsommer.ch">p@pas<span aria-hidden="true" class="hidden"> Pascal Sommer </span>calsommer.ch</a> </p>

<br>

<p> All images Copyright &copy;2013-{{ date('Y') }} Pascal Sommer </p>

<p> All Rights Reserved </p>

<br>

<p>Some other projects of mine: <a href="http://codelis.ch">codelis.ch</a></p>

<br><br><br>

<a class="btn" href="{{ route('home') }}">Back</a>

<p></p>

@endsection
