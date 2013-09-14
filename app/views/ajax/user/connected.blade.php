@extends('layouts.empty')
@section('content')
    @if(isset($user))
        <h1>You're connected!</h1>
        <p>Do you want to <a class="btn-user-disconnect" href="/user/disconnect">disconnect</a>?</p>
    @else
        <h1>Something went wrong</h1>
        <p>I don't even know..</p>
    @endif
@stop