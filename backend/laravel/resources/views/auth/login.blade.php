@extends('layouts.pascal')

@section('content')

<h1>Login</h1>

<br>

<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    @include('layouts.errors')
    <br>

    <style type="text/css">
        .label-column {
            text-align: right;
            padding-right: 8px;
        }
    </style>

    <table class="inline-block">
        <tr>
            <td class="label-column">
                <label for="name">Name </label>
            </td>
            <td>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            </td>
        </tr>
        <tr>
            <td class="label-column">
                <label for="password">Password </label>
            </td>
            <td>
                <input id="password" type="password" name="password" value="{{ old('password') }}" required>
            </td>
        </tr>
        <tr>
            <td class="label-column">
                <label for="remember">Remember Me </label>
            </td>
            <td style="text-align: left">
                <input id="remember" type="checkbox" name="remember" {{ old('remember', true) ? 'checked' : '' }}>
            </td>
        </tr>

    </table>

    <br><br>

    <button class="btn">Login</button>
</form>

@endsection
