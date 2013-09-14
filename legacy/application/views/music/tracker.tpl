<article id="music-tracker" class="grid-12">
  <header class="grid-12">
    <h1>Music</h1>
    <h2>&ldquo;If music be the food of love, play on.&rdquo; &mdash; William Shakespeare </h2>
  </header>

  <section class="feed grid-10">
	  <h2>Recently Played Tracks</h2>
  	{include file='./tracker/feed.tpl'}
  </section>

  <aside class="stats grid-2">
  	{include file='./tracker/stats.tpl'}
  </aside>

  <footer class="grid-10">
  	<p>page <span class="page-current">{$stats.page}</span> of <span class="pages-total">{$stats.totalPages}</span></p>
    <nav class="pagination">
      <a class="first" href="/music/first"><i class="icon-first"></i></a>
      <a class="previous" class="disabled" href="/music/previous"><i class="icon-previous"></i></a>
      <a class="next" href="/music/next"><i class="icon-next"></i></a>
      <a class="last" href="/music/last"><i class="icon-last"></i></a>
    </nav>
  </footer>
</article>