@extends('layouts.master')

@section('page.title')
  Music &ndash; Recently played
@stop

@section('content')
  @include('music.tracker')
  @include('music.tracker.sidebar')
@stop

@section('scripts.footer')
  <script>
    require(['music'], function(Music){
      Music.start('tracker');
    });
  </script>
@stop