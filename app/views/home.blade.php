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
  @include('pages.about.artboard')
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
      My name is Joe Jiko. I’m a <a href="/about/resume#web">web developer</a> &amp; <a href="/about/resume#design">graphic designer</a> living in Florida. I’ve been around the Web professionally since 2002! All of my code is enthusiastically, expertly hand-written&mdash;I use <a href="http://www.sublimetext.com/" target="_blank">Sublime Text 2</a>&mdash;and delicately planned from start to finish.
    </p>
    <p class="base-p">
      Less seriously, I like a lot of other things too. To include a few: <a href="/artwork">art</a>, <a href="/gaming">videogames</a>, and reading.
    </p>

    <p>I pay attention to brands like Google, Facebook, and Twitter and stay tuned to updates on technology such as PHP, CSS, Javascript/jQuery.</p>

    <p>If you'd like to find out more, I invite you to <a href="/blog" class="base-a btn-inline">read my blog</a>.</p>
  </div>

  <h2>Ready to talk?</h2>
  <p class="action-p">
    <a class="base-a btn-inline" href="/contact/message">Send me a message</a> with my contact form or <a href="/contact/quote" class="base-a btn-inline">request a new project</a>
  </p>

  <h2>Need more convincing?</h2>
  <p class="action-p">
    <a class="base-a btn-inline" href="/about/resume">Browse my Résumé</a>
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
<h3>Artwork</h3>
<div class="art-widget">
{{--*/ $artwork = Post::artwork()->orderby(DB::raw("rand()"))->take(6)->get(); $count = count($artwork); /*--}}
{{--*/ $i=0; /*--}}
@foreach($artwork as $i => $art)
{{--*/ $i=$i+1; /*--}}
  @if(1==$i)
  <div class="col img-cover" style="background-image:url({{ $art->guid }});">
    <span class="description">{{ $art->content }}</span>
  </div><!--
  @elseif($count==$i)
  --><div class="col img-cover" style="background-image:url({{ $art->guid }});">
    <span class="description">{{ $art->content }}</span>
  </div>
  @else
  --><div class="col img-cover" style="background-image:url({{ $art->guid }});">
    <span class="description">{{ $art->content }}</span>
  </div><!--
  @endif {{--> --}}
@endforeach
</div>
<h3>Thoughts</h3>
<div class="thought-widget">
@foreach(Post::thoughts()->orderBy('created_at', 'DESC')->take(3)->get() as $i => $post)
<p class="thought-item">
  {{ $post->content }}
  @if(isset($post->user_name))
  <span class="author-info">by <em class="author">{{ $post->user_name }}</em></span>
  @endif
  <time class="created" datetime="{{ $post->created_at }}">{{ $post->created_at }}</time>
</p>
@endforeach
</div>
@stop

@section('content.footer')

@stop