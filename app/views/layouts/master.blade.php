<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="labs no-js"> <!--<![endif]-->
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
  <title>@yield('page.title')</title>
  <link href="https://plus.google.com/110880509059057751100" rel="publisher" />
  <link rel="icon" type="image/ico" href="favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="Creepy, cute art and smart stuff. Join the team! Let's improve ourselves and the world together."/>
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/> -->
  @yield('page.meta')
  {{ cdn_style_min() }}
</head
><body>
<div class="app">
  <header class="site-header">
   @include('layouts.master.header')
  </header>
  <div class="main-view">
    <aside class="site-sidebar">
      <nav class="sidebar-nav-y">
        <!-- inline-block grids are my new favorite thing -->
        <a href="//jiko.us/XHoGuc" title="Joe Jiko on Google Plus"><i class="icon-50 icon-g"></i></a><!--
        --><a href="//jiko.us/12ozDqb" target="_blank" title="@JoeJiko on Twitter"><i class="icon-50 icon-tw"></i></a><!--
        --><a href="//on.fb.me/REuClh" target="_blank" class="Joe Jiko on Facebook"><i class="icon-50 icon-fb"></i></a><!--
        --><a href="//jiko.us/Wx5y3G" class="@JoeJiko on Instagram" target="_blank"><i class="icon-50 icon-ig"></i></a><!--
        --><a href="#"><i class="icon-placeholder"></i></a>
      </nav><!--
      --><div class="sidebar-module-wrapper">
      @include('layouts.master.sidebar')
      </div>
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
          @yield('content.sidebar')
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
<script src="{{ cdn() }}/js/libs/require/require.js"></script>
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
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4976857-2']);
  _gaq.push(['_setDomainName', 'joejiko.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-4976857-4', 'joejiko.com');
  ga('send', 'pageview');

</script>
</body>
</html>