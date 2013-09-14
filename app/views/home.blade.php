@extends('layouts.master')
@section('page.title')
  Welcome!
@stop
@section('content')
<article class="home-article limit-width--reading">
  <h1>"<em>Cute is a word for things that can't kill you</em>"</h1>
  <h2>I don't make websites.</h2>
  <p>I <strong>design</strong> (and code) modern, interactive web applications that will enable you to&mdash;</p>
  <ul class="mission-list list-diamonds">
    <li class="inline-col expect-2 accomplish-goals">accomplish your goals</li><!--
    --><li class="inline-col expect-2 easy-life">make your life easier</li><!--
    --><li class="inline-col expect-2 target-audience">target your audience</li><!--
    --><li class="inline-col expect-2 solve-problems">solve your problems</li>
  </ul>

  <p>I focus on the details &amp; <em>User Experience (UX)</em> to ensure a great response &amp; a long-lasting, positive impression for any user who engages your presence online.</p>

  <p>See? Wasn't that easier to say?</p>

  <h2>Who am I?</h2>
  <p>
    My name is Joe Jiko. I’m a <a href="/about/resume#web">web developer</a> &amp; <a href="/about/resume#design">graphic designer</a> living in Florida. I’ve been around the Web professionally since 2002! All of my code is enthusiastically, expertly hand written&mdash;I use <a href="http://www.sublimetext.com/" target="_blank">Sublime Text 2</a>&mdash;and delicately planned from start to finish.
  </p>
  <p>
    Less seriously, I like a lot of other things too. To include a few: <a href="/artwork">art</a>, videogames, and reading.
  </p>

  <p>I pay attention to things like Google, Facebook, Twitter, Apple, PHP, Javascript/jQuery.</p>

  <h2>Ready to talk?</h2>
  <p>
    <a href="/contact/message">Send me a message</a> with my contact form or <a href="/contact/quote">request a new project</a>
  </p>

  <h2>Need more convincing?</h2>
  <a href="/about/resume">Browse my Résumé</a>
</article>
@stop
@section('content.sidebar')
  <h2>more stuff here</h2>
  <div class="steam-widget" data-config='"limit":3, "widget":true, "name":"steam recently played"'>
    <h3 class="steam-widget-title">Recently played games</h3>
    <div class="feed">
      <div class="loading"><span class="loading-message">loading...</a></div>
    </div>
    <footer class="footer">
      <a href="/gaming">View all (<span class="count">0</span>)</a>
    </footer>
  </div>
  <ul>
    <li><a href="/artwork">Artwork</a>
    <li><a href="/blog">Blog</a>
  </ul>
  <p>that's it for now..</p>
@stop
@section('content.footer')
  xoxoxo
@stop