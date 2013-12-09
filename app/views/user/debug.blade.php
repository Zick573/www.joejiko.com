@extends('layouts.master')
@section('page.title')
  (!) DEBUG
@stop
@section('content')
  Let's get some information..<br>
  Logged in: {{ $is_logged }} <br>
  From session: {{ $from_session }} <br>
  Other stuff: {{ var_dump($other_stuff) }} <br>
@stop