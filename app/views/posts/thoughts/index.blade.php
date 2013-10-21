@extends('layouts.master')
@section('page.title')
I think..
@stop
@section('content.header')
@if (!Auth::guest())
  @if (Auth::user()->isAdmin())
    <a href="/thought/create">Post something</a>
  @endif
@endif
@stop
@section('content')
  @if(isset($result))
   {{ var_dump($result) }}
  @endif

  @foreach ($posts as $post)
    <p class="thought-item"> {{ $post->content }} <span class="author-info">by <em class="author">{{ $post->user_name }}</em></span></p>
    @if (!Auth::guest())
      @if (Auth::user()->isAdmin())
        <div class="admin-controls">
          <a href="/admin/posts/edit?id={{ $post->id }}">[edit]</a>
          <a href="/admin/posts/delete?id={{ $post->id }}">[delete]</a>
        </div>
      @endif
    @endif
  @endforeach
@stop