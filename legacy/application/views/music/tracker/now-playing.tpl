<li class="now-playing">
	<img src="{$track.img|default:'missing.jpg'}">
  <img class="spotify" src="//joejiko.com/images/music/logo-spotify19.png" title="{$track.name|default:'unknown'}">
	{$track.artist|default:'unknown'} &mdash; {$track.name|default:'unknown'} <span class="timestamp">now playing!</span>
  <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://joejiko.com/music" data-text="{$track.name|default:'unknown'} - {$track.artist|default:'unknown'}" data-via="JoeJiko" data-related="JJCOMS" data-hashtags="JJCOM,NP">Tweet</a>
</li>