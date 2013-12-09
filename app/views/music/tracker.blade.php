@extends('layouts.master')
@section('page.title')
  Music tracker
@stop
@section('content')
<h1>Music</h1>
<h2>&ldquo;If music be the food of love, play on.&rdquo; &mdash; William Shakespeare</h2>
<article class="music-tracker">
  <section class="feed">
    <header>
      <h2 class="feed-header">Recently Played Tracks</h2>
    </header>
    @include('music.tracker.feed')
    <footer class="feed-controls">
      <p>page <span class="page-current">1</span> of <span class="pages-total">4010</span></p>
      <nav class="pagination">
        <a class="first" href="/music/first">
          <i class="icon-first"></i>
        </a>
        <a class="previous" href="/music/previous">
          <i class="icon-previous"></i>
        </a>
        <a class="next" href="/music/next">
          <i class="icon-next"></i>
        </a>
        <a class="last" href="/music/last">
          <i class="icon-last"></i>
        </a>
      </nav>
    </footer>
  </section>
</article>
@stop