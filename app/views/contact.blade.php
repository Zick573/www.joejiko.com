@extends('layouts.master')
@section('page.title')
  Contact me
@stop


@section('page.styles')

  @parent
@stop

@section('body.sidebar')
  <a class="btn-default" href="/ask">Ask a question</a>
@stop

@section('content')
<article class="base-article">
  <h1>Contact</h1>
  @if(Auth::guest() || !isset(Auth::user()->email))
    <p>
      <a href="/user/connect/google" class="btn-connect-g">
        <i class="sign-in-google"></i>
        Connect with Google
      </a> to create an account & send me a direct message.
    </p>
  @else
    @include('contact.message')
  @endif

  <p>
    If you don't want to create an account, The best way to contact me is by using Google+ hangouts.
  </p>

  <p>
  <div style="display: inline-block; vertical-align: middle;"><div class="g-follow" data-annotation="none" data-height="24" data-href="//plus.google.com/u/0/110880509059057751100" data-rel="author"></div></div>
    me on <a href="http://jiko.us/XHoGuc">Google+</a> and send a message</a></p>

  <!-- <p><div class="g-hangout" data-render="createhangout"></div></p> -->

  <!-- <div class="g-person" data-href="//plus.google.com/110880509059057751100" data-theme="dark" data-layout="landscape" data-rel="author"></div> -->

  <p>
    If you're against Google+ or something, you can
    <a href="http://jiko.us/1fMRPOF" class="btn-connect-t">
      <i class="sign-in-twitter"></i>
      Tweet me
    </a> @JoeJiko<br>
    or try one of my <a href="/contact/other">other profiles</a>
  </p>
  <p><small>(yes, this is a test)</small></p>
</article>
@stop

@section('scripts.footer')
  <script type="text/javascript">
    (function() {
      var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
      po.src = 'https://apis.google.com/js/platform.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
  </script>
@stop