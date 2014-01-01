@extends('layouts.admin.master')
@section('page.title')
  Admin
@stop
@section('content')
  <h1>You can see this because you're an Admin!</h1>
  <ul>
    <li><a href="/admin/artwork">Artwork</a>
    <li><a href="/admin/twitter-archive">Twitter Archive</a>
  </ul>
@stop