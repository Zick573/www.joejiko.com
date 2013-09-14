  <nav class="site-nav-x">@include('layouts.master.header.navx')</nav>
  <span class="edge-triangle">&nbsp;</span>
  <div class="app-menu app-menu--loading">
    <div class="site-logo-bundle">
      <div class="menu-trigger">
        <span class="menu-instructions">menu &rarr;</span>
        <i class="batch" data-icon="&#xF0AA;"></i>
      </div>
      <div itemscope itemtype="http://schema.org" class="site-logo">{{ $logo }}</div>
      <span class="diamondMask">&nbsp;</span>
      <span class="diamond">&nbsp;</span>
    </div>
    <div class="site-nav-wrap">@include('layouts.master.header.menu')</div>
  </div>
  <div class="site-search-module">@include('layouts.master.header.search')</div>
  <nav class="user-nav">@include('user.nav')</nav>