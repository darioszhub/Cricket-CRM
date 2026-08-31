@extends('layout')
@section('title','Login')
@section('content')
<h1>Login</h1>
@if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>
@endsection