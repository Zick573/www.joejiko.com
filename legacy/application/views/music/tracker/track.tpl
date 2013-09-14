<li>
  <img src="//joejiko.com/images/music/logo-spotify19.png" class="spotify" title="{$track.name|default:'unknown'}">
  <span>{$track.artist|default:'unknown'} &mdash; <a href="{$track.url|default:'#'}" target="_blank">{$track.name}</a></span>
  <time class="timeago timestamp" datetime="{$track.date|iso_date_format|default:'unknown'}">{$track.date|iso_date_format|default:'unknown'}</time>
  <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://joejiko.com/music" data-text="{$track.name|default:'unknown'} - {$track.artist|default:'unknown'}" data-via="JoeJiko" data-related="JJCOMS" data-hashtags="JJCOM">Tweet</a>
</li>