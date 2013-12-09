  <nav class="site-nav-x">@include('layouts.master.header.navx')</nav>
  <span class="edge-triangle">&nbsp;</span>
  <div class="app-menu">
    <div class="site-logo-bundle">
      <div itemscope itemtype="http://schema.org" class="site-logo">{{ $logo }}</div>
      <!-- <span class="diamondMask">&nbsp;</span> -->
      <span class="diamond"><span class="diamond diamond-2">&nbsp;</span></span>
    </div>
    <div class="site-nav-wrap">@include('layouts.master.header.menu')</div>
  </div>
  <div class="menu-trigger">
        {{-- <span class="menu-instructions">menu &rarr;</span> --}}
        {{-- <i class="batch" data-icon="&#xF0AA;"></i> --}}
        <i class="modernpics" data-icon="▾"></i><!--
        --><div class="menu-item--active">
          <i class="batch" data-icon="&#xF161;"></i><!--
          --><span class="menu-instructions">Home</span>
        </div>
        <div class="menu-item--active menu-blog">
          <a href="/blog">
          <i class="batch" data-icon="&#61445;"></i><!--
          --><span class="menu-instructions">Blog</span>
          </a>
        </div>
  </div>

  <div class="site-search-module">@include('layouts.master.header.search')</div>
  <nav class="user-nav">@include('user.nav')</nav>