<!doctype html>
<meta charset="utf8">
<style>
  * { box-sizing: border-box; -moz-box-sizing: border-box;}
  html { background: url(https://si0.twimg.com/profile_background_images/819113113/f18097b7432a61cc1b306846dab17c16.jpeg) fixed right top #000; font-size: 100%; font-family: arial, sans-serif; }
  body { margin: 0;}
  #app { width: 840px; padding: 15px; margin: 0 auto; background: rgba(0,0,0,.1);}
  input, button  { border: none; padding: 5px;}
  .app-header { padding-bottom: 15px;}
  .nav-primary { width: 60%;}
  .search { width: 40%; text-align: right;}
  .view { background: #fff;}
  .content-header { padding: 15px; border-bottom: 1px solid rgba(0,0,0,.1); background-color: rgba(0,0,0,.1);}
  .view-title { margin: 0 0 15px;}
  .info-matches, .info-total { display: inline-block; margin: 0;}
  .tweets { margin: 0; padding: 0; list-style: none;}
  .tweet { padding: 15px; border-bottom: 1px solid rgba(0,0,0,.1);}
  .col { display: inline-block; vertical-align: top;}
  .col.text { width: 80%;}
  .col.link-tweet { width: 20%; text-align: right;}
  .timestamp { font-size: .8rem;}
  .end-of-tweets { background: rgba(0,0,0,.1);}
</style>
<div id="app">
  <a id="top"></a>
  <header class="app-header">
    <nav class="col nav-primary">
      <a href="/">&larr; to JoeJiko.com</a>
    </nav><!--
    -->{{ Form::open(['method' => 'GET', 'class' => 'col search']) }}
    {{ Form::text('q') }}
    {{ Form::button('search', ['type'=>'submit'])}}
    {{ Form::close() }}
  </header>
  <div class="view">
    <div class="content">{{ @$content }}</div>
  </div>
  <footer class="app-footer"></footer>
</div>
@include('vendor.google.analytics')