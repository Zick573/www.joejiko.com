<li class="now-playing">
  <img src="{{ $track->img }}"
       alt="{{ $track->name }}">
  <img class="spotify"
       title="{{ $track->name }}"
       src="{{ cdn_img() }}vendor/spotify/logo-spotify19.png" alt="spotify icon">
  <span class="track-info">
    {{ $track->artist }} &mdash;
    <a class="track-title"
       href="{{ $track->url }}" target="_blank">
       {{ $track->title }}
    </a>
  </span>
  <time class="timeago play-timestamp"
        datetime="{{ date("c") }}">
    {{ date("c") }}
  </time>
  <span class="now-playing-display">now playing!</span>
  <a class="twitter-share"
     href="//twitter.com/intent/tweet?url={{ urlencode("http://www.joejiko.com/music") }}&via=joejiko&text={{ urlencode($track->name . "-" . $track->artist) }}&hashtags=NP,JJCOMS&related=jjcoms">
    tweet
  </a>
</li>