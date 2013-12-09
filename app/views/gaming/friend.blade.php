@extends('layouts.master')
@section('page.title')
  Game over, man.
@stop
@section('content')
<article class="base-article page gaming">
  <h1>Gaming</h1>
  {{ var_dump($games) }}
@stop
@section('content.footer')
  Wanna play? <a href="http://steamcommunity.com/id/joejiko" target="_blank">Add me to your friend list</a>
@stop