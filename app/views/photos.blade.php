@extends('layouts.master')
@section('page.title')
  Photos worth a thousand words. What do they say to you?
@stop
@section('content')
<article class="base-article">
  <h1>Photos</h1>
  <h2>Nothing to see here just yet.</h2>
  <p class="base-p">
    Click below to <a href="http://jiko.us/Wx5y3G" target="_blank">follow me on Instagram</a> for now<br><br>
    <a href="http://jiko.us/Wx5y3G" target="_blank">http://instagram.com/joejiko</a><br><br>
    <a href="http://jiko.us/Wx5y3G" target="_blank">
      <img style="width: 100%; height: auto;" src="{{ cdn_img() }}shared/profiles/instagram.jpg" alt="@joejiko on Instagram.com">
    </a>
  </p>
</article>
@stop