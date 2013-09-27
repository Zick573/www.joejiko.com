<li class="track">
  <img class="spotify" title="{{ $track->name }}" src="{{ cdn_img() }}vendor/spotify/logo-spotify19.png">
  <span class="track-info">
    {{ $track->artist }} &mdash;
    <a class="track-title" href="{{ $track->url }}" target="_blank">{{ $track->title }}</a>
  </span>
  <time class="timeago play-timestamp" datetime="{{ date("c", $track->date) }}">{{ date("c", $track->date) }}</time>
  <a class="twitter-share" href="//twitter.com/intent/tweet?url={{ urlencode("http://www.joejiko.com/music") }}&via=joejiko&text={{ urlencode($track->name . "-" . $track->artist) }}&hashtags=JJCOMS&related=jjcoms">tweet</a>
</li>