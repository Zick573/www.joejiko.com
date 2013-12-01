@extends('layouts.master');
@section('page.title')
  Show your support!
@stop
@section('content')
<header class="base-header base-article">
  <h1>Show your support</h1>
  <h2>(for me and my life)</h2>
</header>

<article class="base-article">
  {{ $amazon_wishlist }}
</article>

<article class="base-article">
  <h3>Amazon wishlist</h3>
  <script type="text/javascript" src="http://ws.amazon.com/widgets/q?ServiceVersion=20070822&MarketPlace=US&ID=V20070822/US/joji-20/8004/d5ef8eb5-e131-468f-87ea-9bb47987b038"> </script>
</article>
@stop