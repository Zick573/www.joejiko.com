@extends('layouts.master')
@section('page.title')
  Game over, man.
@stop
@section('content')
<article class="page gaming">
  <h1>Gaming</h1>
  <h2>Currently playing</h2>
  <a class="now-playing ffxiv" href="http://www.finalfantasyxiv.com/" target="_blank" title="FFXIV official site">
    <img src="pages/gaming/xivlogo.png" alt="Final Fantasy XIV">
  </a>
  <h2>Recently played games (on <a href="http://steamcommunity.com/id/joejiko" target="_blank">Steam</a>)</h2>
  <h3>Played within the past 2 weeks</h3>
  <div class="steam">
    <strong class="steam-title">Me</strong>
    <div class="feed">
      <div class="loading"><span class="loading-message">loading...</a></div>
    </div>
  </div><!--
  --><aside class="steam-friends">
    <strong class="steam-friends-title">Friends</strong>
    <div class="feed">
      <div class="loading"><span class="loading-message">loading...</a></div>
    </div>
  </aside>
</article>
@stop
@section('content.footer')
  Wanna play? <a href="http://steamcommunity.com/id/joejiko" target="_blank">Add me to your friend list</a>
@stop