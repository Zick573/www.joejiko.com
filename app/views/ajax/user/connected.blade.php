@extends('layouts.empty')
@section('content')
    @if(Auth::check())
        <h1>You're connected!</h1>
    @else
        <h1>Something went wrong</h1>
        <p>I don't even know..</p>
    @endif
@stop