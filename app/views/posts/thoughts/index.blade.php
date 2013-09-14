@extends('layouts.master')
@section('page.title')
I think..
@stop
@section('content.header')
@if (isset($user) && $user->name !== "Anonymous")
  @if (isset($user->role) && $user->role <= 2)
    <a href="/thought/create">Post something</a>
  @endif
@endif
@stop
@section('content')
  @foreach ($posts as $post)
    <p class="thought-item"> {{ $post->content }} <span class="author-info">by <em class="author">{{ $post->user_name }}</em></span></p>
    @if (isset($user) && $user->name !== "Anonymous")
      @if (isset($user->role) && $user->role <= 2)
        <div class="admin-controls">
          <a href="/admin/posts/edit?id={{ $post->id }}">[edit]</a>
          <a href="/admin/posts/delete?id={{ $post->id }}">[delete]</a>
        </div>
      @endif
    @endif
  @endforeach
@stop