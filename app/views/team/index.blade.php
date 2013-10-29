@extends('layouts.master')
@section('page.title')
  @if (!Auth::guest() && Auth::user()->isTeam())
    Welcome Jikosian! (Team Jiko)
  @else
    Not allowed!
  @endif
@stop
@section('content')
<article class="base-article">
  @if (!Auth::guest() && Auth::user()->isTeam())
    <h2>Hello {{ Auth::user()->name }}!</h2>
    <p>You can see this because you're part of the team! 8-]</p>
  @else
    <h1 class="alert unauthorized unauthorized-bg">This page is for Team Jiko only!</h1>
    <h2>Would you like to <a class="btn btn-purple" href="/team/join">join the team?</a></h2>
  @endif
</article>
@stop