@extends('layouts.master')
@section('page.title')
All posts
@stop
@section('content.header')

@stop
@section('content')
<article class="base-article">
  {{ var_dump($posts) }}
</article>
@stop