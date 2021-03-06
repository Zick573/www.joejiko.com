  <!-- <span class="edge-triangle">&nbsp;</span> -->
  <div class="app-menu">
    <div class="site-logo-bundle">
      <span class="app-loading-msg loading-msg">Loading..</span>
      <div itemscope itemtype="http://schema.org" class="site-logo">{{ $logo }}</div>
      <!-- <span class="diamondMask">&nbsp;</span> -->
      <!--<span class="diamond"><span class="diamond diamond-2">&nbsp;</span></span>-->
    </div>
    <nav class="site-nav-x">@include('layouts.master-2.header.navx')</nav>
    <div class="site-nav-wrap">@include('layouts.master-2.header.menu')</div>
  </div>
  <div class="menu-trigger">
        {{-- <span class="menu-instructions">menu &rarr;</span> --}}
        {{-- <i class="batch" data-icon="&#xF0AA;"></i> --}}
        <i class="modernpics" data-icon="▾"></i>{{--<!--
        --><div class="menu-item--active">
          <i class="batch" data-icon="&#xF161;"></i><!--
          --><span class="menu-instructions">Home</span>
        </div>--}}
  </div>
  {{--
  <div class="menu-item--active menu-blog">
          <a href="/blog">
          <i class="batch" data-icon="&#61445;"></i><!--
          --><span class="menu-instructions">Blog</span>
          </a>
  </div>
  --}}

  <div class="site-search-module">
    @include('layouts.master-2.header.search')
  </div>
  <div class="site-head-sidebar">
    <nav class="user-nav">@include('user.nav')</nav>
  </div>
  <nav class="sidebar-nav-y">
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