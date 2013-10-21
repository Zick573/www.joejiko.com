@extends('layouts.master')
@section('page.title')
  Artwork. Pokehumans. Iron man christmas comic. Grumpszilla.
@stop
@section('content')
<article class="artwork">
  <header>
  <h1>Artwork</h1>
  </header>
  @if(count($artworks))
  @foreach($artworks as $artwork)
  <img src="{{ $artwork->guid }}" alt="{{ $artwork->title }}">
  @endforeach
  @else
    Nothing here :(
  @endif
@stop
@section('content.footer')
  Load more..
@stop
@section('scripts.footer')
<script src="/js/app/artwork/viewer.js" async="true"></script>
@stop