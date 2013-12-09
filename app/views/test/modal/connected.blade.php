@extends('layouts.master')
@section('page.title')
  TEST
@stop

@section('scripts.footer')
  <script src="{{ js_path() }}user/connected.js" async="true"></script>
@stop