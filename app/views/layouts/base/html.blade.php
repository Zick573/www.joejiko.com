<!doctype html>
<title>{{ $title }}</title>
<style>
  * {box-sizing: border-box; -moz-box-sizing: border-box; }
  body { margin: 0;}
  .nav-primary { display: block; background: rgba(0,0,0,.9); color: #fff;}
  .nav-primary .link { display: inline-block; color: #fff; padding: 1em; padding: 1rem; border-right: 1px solid #fff; text-decoration: none;}
  .main { padding: 1em; padding: 1rem;}
</style>
<header>
  <nav class="nav-primary nav-simple">
    <a class="link" href="http://www.joejiko.com">JoeJiko.com</a>
    <a class="link" href="http://www.joejiko.com/artwork">Artwork</a>
    <a class="link" href="http://www.joejiko.com/blog">Blog</a>
  </nav>
</header>
<div class="main">
{{ $content }}
</div>