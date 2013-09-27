<ul class="feed-content">
  @foreach($tracks as $track)
    @if($track->type == 'now playing')
      @include('music.tracker.now-playing')
    @else
      @include('music.tracker.track')
    @endif
  @endforeach
</ul>