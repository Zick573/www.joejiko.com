@extends('layouts.master')
@section('page.title')
  Artwork. Pokehumans. Iron man christmas comic. Grumpszilla.
@stop
@section('content')
<article class="artwork">
  <header>
  <h1>Artwork</h1>
  @if(!Auth::guest() && Auth::user()->isAdmin())
  <a href="/admin/artwork">add artwork</a>
  @endif
  </header>
  @if(count($artworks))
  <div class="collections">
    @foreach($artworks as $i => $artwork)
    @if (0==$i)
    <div class="art">
    @else
    --><div class="art">
    @endif
      @if($artwork->excerpt)
      <img src="{{ $artwork->guid }}" alt="{{ $artwork->title }}" data-img-full="{{ $artwork->guid }}">
      @else
      <img src="{{ $artwork->guid }}" data-img-full="{{ $artwork->guid }}" alt="{{ $artwork->title }}">
      @endif
    @if ( count($artworks) !== ($i+1))
    </div><!--
    @else
    </div>
    @endif
    @endforeach
  </div>
  @else
    Nothing here :(
  @endif
@stop
@section('content.footer')

@stop
@section('scripts.footer')
<script src="/js/app/artwork/viewer.js" async="true"></script>
@stop