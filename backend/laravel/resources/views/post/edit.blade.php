@extends('layouts.pascal')

@section('title', 'Edit Post - Pascal Sommer')

@section('content')

<h1>Edit Post</h1>

@include('post.form', ['action' => route('updatePost', compact('post'))])

<br><br><br>

@endsection
