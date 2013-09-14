@extends('layouts.master')
@section('page.title')
  Page not found (404)
@stop

@section('content')
  <h1>This page doesn't exist</h1>
  <p>Request: {{ $request }}</p>
  <p>Were you looking for one of these existent pages?</p>
@stop