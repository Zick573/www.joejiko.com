<!doctype html>
<html>
  <head>
    <title>@yield('page.title')</title>
    <style>
      * { box-sizing: border-box; -moz-box-sizing: border-box; }
      body { margin: 0;}
      .admin-header { height: 80px; border-bottom: 2px solid #000; padding-bottom: 5px; text-align: center; }
      .admin-header svg { height: 80px; }
      .main { width: 980px; margin: 0 auto; }
      .admin-controls { width: 30%; }
      .content { width: 70%; }
      .col { display: inline-block; vertical-align: top; }
    </style>
    @yield('page.styles')
  </head>
<body>
  <header class="admin-header">
    {{ $logo }}
  </header>
  <div class="main">
    <aside class="admin-controls col">
      @yield('controls')
    </aside><!--
    --><div class="content col">
      @yield('content')
    </div>
  </div>
  <footer class="admin-footer">@yield('footer')</footer>
  @yield('page.scripts')
</body>
</html>