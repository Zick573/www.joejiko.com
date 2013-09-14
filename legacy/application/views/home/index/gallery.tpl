<section id="gallery">
  <h2>Recent Photo Uploads</h2>
  {foreach $gallery as $image}
  <article id="{$image.id}">
  	<a href="/p/{$image.id}" title="view full size image">
    	<img src="//cdn.joejiko.com/images/{$image.filename}">
  	</a>
    <span class="caption">{$image.caption}</span>
    <span class="timestamp">{$image.created}</span>
	</article>
  {/foreach}
</section>