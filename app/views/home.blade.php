@extends('layouts.master')
@section('page.title')
  Joe Jiko Web & Design &mdash; Documentation of my life, thoughts, and obsessions.
@stop
@section('page.description')
  Life, thoughts, and obsessions.
@stop
@section('content')
<article class="home-article limit-width--reading">
  <h1>"<em>Cute is a word for things that can't kill you</em>"</h1>
  <h2>I don't make websites.</h2>
  <div class="base-content">
    <p class="base-p">I <strong>design &amp; code</strong> modern, interactive web applications that will enable you to</p>
  </div>
  <ul class="mission-list list-diamonds">
    <li class="inline-col expect-4 accomplish-goals"><span class="mission-label">accomplish your goals</span></li><!--
    --><li class="inline-col expect-4 easy-life"><span class="mission-label">make your life easier</span></li><!--
    --><li class="inline-col expect-4 target-audience"><span class="mission-label">target your audience</span></li><!--
    --><li class="inline-col expect-4 solve-problems"><span class="mission-label">solve your problems</span></li>
  </ul>

  <div class="base-content">
    <p class="base-p">I focus on the details &amp; <em>user experience</em> to ensure a great response &amp; a long-lasting, positive impression for any user who engages your presence online.</p>
    <p class="base-p">See? Wasn't that easier to say?</p>
  </div>
  <h2>Who am I?</h2>
  <div class="base-content">
    <p class="base-p">
      My name is Joe Jiko. I’m a <a href="/about/resume#web">web developer</a> &amp; <a href="/about/resume#design">graphic designer</a> living in Florida. I’ve been around the Web professionally since 2002! All of my code is enthusiastically, expertly hand written&mdash;I use <a href="http://www.sublimetext.com/" target="_blank">Sublime Text 2</a>&mdash;and delicately planned from start to finish.
    </p>
    <p class="base-p">
      Less seriously, I like a lot of other things too. To include a few: <a href="/artwork">art</a>, <a href="/gaming">videogames</a>, and reading.
    </p>

    <p>I pay attention to brands like Google, Facebook, and Twitter and stay tuned to updates on technology such as PHP, CSS, Javascript/jQuery.</p>

    <p>If you'd like to find out more, I invite you to <a href="/blog">read my blog</a>.</p>
  </div>

  <h2>Ready to talk?</h2>
  <p class="action-p">
    <a class="base-a" href="/contact/message">Send me a message</a> with my contact form or <a href="/contact/quote">request a new project</a>
  </p>

  <h2>Need more convincing?</h2>
  <p class="action-p">
    <a class="base-a" href="/about/resume">Browse my Résumé</a>
  </p>
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