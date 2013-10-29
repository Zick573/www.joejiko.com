@extends('layouts.master')

@section('page.title')
  Join the team! #smartculture
@stop

@section('content')
<article class="base-article">
  <h1>Team Jiko</h1>
  <h2>Intro</h2>
  <p>Do you seek knowledge?<br>
  Do you read? Write? Make art?</p>
  <p>Do you <em>create</em> things?</p>
  <p><strong>You're welcome here.</strong></p>

  <h2>How to Join</h2>
  <p class="alert">
    We're not taking applications right now, but contact me if you want to join.<br>
    (yes, this is a test)
  </p>
  @if(Auth::guest())
  <p>
    You must connect an account to join, so go ahead and do that.
    <a class="user-nav-link btn-green btn-user-connect" href="/user/connect">
      <i class="batch" data-icon="&#xF046;"></i> click to connect now!
    </a>
  </p>
  @endif
</article>
@stop