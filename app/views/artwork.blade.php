@extends('layouts.master')
@section('page.title')
  Artwork. Pokehumans. Iron man christmas comic. Grumpszilla.
@stop
@section('content')
<article class="artwork">
  <header>
  <h1>Artwork</h1>
  </header>
  <h2 class="artwork-category-title">Pok&eacute;humons</h2>
  <img src="{{ cdn_img() }}artwork/pokehuman-002.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/jikomander.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/pokehuman-003.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/pokehuman-004.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/pokehuman-005.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/pokehuman-006.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/pokehuman-007.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/pokehuman-008.jpg">
  <h2 class="artwork-category-title">Uncategorized</h2>
  <img src="{{ cdn_img() }}artwork/armored-grump-3.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/grumpszilla.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/joannabearr.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/tattoo-artist-business-cards.jpg"><!--
  --><img class="full" src="{{ cdn_img() }}artwork/iron-man-christmas.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/clockwork-eyes.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/zeah-missing.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/jiko-face-3.jpg"><!--
  --><img class="full" src="{{ cdn_img() }}artwork/no-cuddling.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/zeah-faces.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/black-white-owl-zeah.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/zombietart.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/zeah-nerd.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/time-to-die.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/jikowolf.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/jikobot.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/jikoghost.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/creative-spine.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/megara-polar-bears.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/jiko-business-card.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/zeah-traffic.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/zeah-wallpaper.jpg"><!--
  --><img src="{{ cdn_img() }}artwork/say-hello-to-the-robots.jpg">
</article>
@stop
@section('content.footer')
  Load more..
@stop
@section('scripts.footer')
<script src="/assets/js/app/artwork/viewer.js" async="true"></script>
@stop