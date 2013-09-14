@extends('layouts.master')
@section('page.title')
  You're connected!
@stop
@section('content')
@if(isset($user))
    <h1>You're connected!</h1>
    <p>Do you want to <a href="/user/disconnect">disconnect</a>?</p>

    <?php var_dump(Session::all()); ?>
@else
    <h1>Something went wrong</h1>
    <p>I don't even know..</p>
@endif
@stop