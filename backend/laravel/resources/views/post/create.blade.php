@extends('layouts.pascal')

@section('title', 'New Post - Pascal Sommer')

@section('content')

<h1>New Post</h1>

@include('post.form', ['action' => route('storePost')])

<br><br><br>

@endsection
