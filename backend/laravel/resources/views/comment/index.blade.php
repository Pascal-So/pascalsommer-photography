@extends('layouts.pascal')

@section('title', 'Comments - Pascal Sommer')

@section('content')

<h1>Comments</h1>

@if($comments->isEmpty())
    <p>No comments.</p>
@else
    <p>{{ $comments->count() }} comments</p>
@endif

@include('comment.list', ['comments' => $comments, 'admin_overview' => true])

@endsection
