@extends('layouts.empty')
@section('content')
    @if(Auth::check())
      <h1>Something went wrong</h1>
      <p>I don't even know..</p>
    @else
      <h1>I've disconnected you!</h1>
      <p>As far as I'm concerned, you were never here.</p>
    @endif
@stop