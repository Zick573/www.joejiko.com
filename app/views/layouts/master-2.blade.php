<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
@if(app()->environment() == 'local')
<html class="labs no-js">
@else
<html class="no-js">
@endif
 <!--<![endif]-->
<!--
Hello source reader!
Let's be friends.

If you have questions about anything
you see here, please ask! me@joejiko.com

Leave your console open maybe?
-->
<head>
  <!--
    note: head/body/html tags are supposedly optional now..
    http://www.w3.org/TR/2011/WD-html5-20110525/syntax.html#optional-tags

    but Chrome gave me shit so.. >:[
  -->
  <link href="https://plus.google.com/110880509059057751100" rel="publisher" />
  <link rel="icon" type="image/ico" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>
    @section('page.title')
      Software developer, artist & designer &mdash; Internet playground. Documentation of my life, thoughts, and obsessions.
    @show
  </title>

  <meta name="description" content="I'm a sucker for pretty faces & good art. I like creepy things, sci-fi, & super heroes with no powers. Artist. On a path to become less human.">

  @yield('page.meta')
  {{-- Global styles --}}
  {{-- Theme styles --}}
  {{ HTML::style('/css/styles-master.min.css') }}
  {{-- STYLE OVERRIDES --}}
  <style>
  @section('page.styles')
  @show
  </style>
</head>
<body>
<div class="app app--loading">
  <header class="site-header">
   @include('layouts.master-2.header')
  </header>
  <div class="main-view">
    {{-- @Todo: move to config --}}
    {{--<aside class="site-sidebar" style="background-image:url({{ cdn() }}/img/artwork/jiko-face-3.jpg); background-size: 150%;">--}}
    <aside class="site-sidebar" style="background-image:url(http://distilleryimage8.ak.instagram.com/3148ece6a24e11e394fa12bd164c5014_8.jpg); background-size: cover;">
      <div class="sidebar-module-wrapper">
      @section('body.sidebar')
      @include('layouts.master.sidebar')
      @show
      </div><!--
      --><nav class="sidebar-nav-y">
        <!-- inline-block grids are my new favorite thing -->
        <a href="//plus.google.com/110880509059057751100?rel=author" title="Joe Jiko on Google Plus" target="_blank">
          <i class="icon-50 icon-g"></i>
        </a><!--
        --><a href="//jiko.us/12ozDqb" target="_blank" title="@JoeJiko on Twitter">
          <i class="icon-50 icon-tw"></i>
        </a><!--
        --><a href="//on.fb.me/REuClh" target="_blank" class="Joe Jiko on Facebook">
          <i class="icon-50 icon-fb"></i>
        </a><!--
        --><a href="//jiko.us/Wx5y3G" class="@JoeJiko on Instagram" target="_blank">
          <i class="icon-50 icon-ig"></i>
        </a><!--
        --><a href="#"><i class="icon-placeholder"></i></a>
      </nav>
    </aside><aside class="site-sidebar-holder">&nbsp;</aside><!--
    --><div class="main" id="main">
      @if(Session::has('flash_notice'))
        <div class="notice">{{ Session::get('flash_notice') }}</div>
      @endif
      <section class="main-content" id="content">
        <header class="main-header">
          @yield('content.header')
        </header>
        <div class="main-article">
          {{-- Content --}}
          @yield('content')
        </div><!--
        --><aside class="main-sidebar">
          {{-- @yield('content.sidebar') --}}
        </aside>
        <footer class="main-footer">
          @yield('content.footer')
        </footer>
      </section>
    </div>
  </div>
  <footer class="site-footer">
    @include('layouts.master.footer')
  </footer>
</div>
<!-- switched to AMD -->
<script src="{{ cdn() }}js/libs/require/require.js"></script>
<script src="{{ js_path() }}app.js" async="true"></script>

@if(Session::has('user_connected'))
<script src="{{ js_path() }}user/connected.js" async="true"></script>
@endif

@if(Session::has('user_disconnected'))
<script src="{{ js_path() }}user/disconnected.js" async="true"></script>
@endif
<!-- dojo.. some day maybe
  <script src="//ajax.googleapis.com/ajax/libs/dojo/1.9.0/dojo/dojo.js"></script>
-->
@yield('scripts.footer')

<!-- you mean there's a new analytics syntax to learn? *facepalm -->
@include('vendor.google.analytics')
</body>
</html>