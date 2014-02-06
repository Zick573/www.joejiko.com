<!doctype html>
<meta charset="utf-8">
<style>
* { box-sizing: border-box; -moz-box-sizing:border-box;}
html { background: #eee;}
body { font-family: arial, sans-serif; margin: 0; width: 30%; background: #fff; box-shadow: -1px 0 0 rgba(0,0,0,.1) inset;}
h1 {margin:0;}
.app {}
.app-footer { background: rgba(0,0,0,.1); padding: 15px; font-size: .8rem; text-align: center;}
.col { vertical-align: top; display: inline-block;}
.sms { padding: 15px 0;}
.sms-item { box-shadow: 0 -2px 0 rgba(0,0,0,.1) inset; border: 1px solid rgba(0,0,0,.1); margin: 15px; position: relative;}
.date-received { position: absolute; top: 5px; right: 5px; font-size: 1.3rem; color: rgba(0,0,0,.5);}
.from-name {}
.from-phone {
    display: inline-block;
    font-size: 0.5rem;
    padding: 0 1rem;
    transform: skew(-71deg);
    cursor: pointer;
}
.from-phone:hover {
  font-size: 1rem;
  transform: skew(0deg);
}
.message { padding: 15px; }
.reply-text { height: 2rem; width: 80%; }
.btn-reply { width: 20%; border: none; height: 2rem;}
i[class^="icon-"] { font-style: normal;}
</style>
<div class="app">
  <header class="app-header">
    <h1>★ <a href="/">Home</a></h1>
  </header>
  <div class="sms">
    {{ $output }}
  </div>
  <footer class="app-footer">
    {{ $totals }}
  </footer>
</div>