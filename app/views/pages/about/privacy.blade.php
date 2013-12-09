@extends('layouts.master')
@section('page.title')
Privacy policy
@stop
@section('content')
<article class="base-article">
  <h1>Privacy policy</h1>
  <p class="base-p">
    LOL. There's nothing private on the Internet.<br><br>
    <img src="{{ cdn_img() }}clips/do-you-trust-me.gif" alt="do you trust me?" width="100%" style="display:block;">
@if(Auth::guest())
    <a class="user-nav-link btn-green btn-user-connect" href="/user/connect">
      <i class="batch" data-icon="&#xF046;"></i> click to connect to JoeJiko.com now!
    </a>
@endif
  </p>
<ul>
<li>Certain pages require you <a class="btn btn-user-connect" data-action="user.connect">connect</a> in order to view them.
<li>You must have an account if you want to <a class="btn btn-purple" href="/team" data-action="team.join">join the team</a>
</ul>
</article>
@stop