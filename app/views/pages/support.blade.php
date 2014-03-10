@section('page.title')
  Show your support!
@stop
@section('content')
<header class="base-header base-article">
  <h1 style="margin-bottom:0;">Show your support</h1>
  <h2 style="padding-top: 0; margin-top: 0;">(for me and my life)</h2>
</header>
<article class="base-article">
  <p>If you love me, appreciate something I've done, or you're feeling particularly generous, here are some ideas for you! Checkout out my "wishlist" of things. It usually includes geek necessities, "nice to have" technology, or clothing.</p>
  <p>If you're not really into buying things, I feel ya. I really like when people <a href="/blog">comment on my blog</a> or <a href="/artwork">share my artwork</a>. It costs nothing but your time and really means a lot to me. Thanks!</p>
</article>
<article class="base-article">
  {{ $amazon_wishlist }}
</article>
@stop