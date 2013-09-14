<article id="page-photos">
  <header class="grid-12">
    <hgroup>
      <h1>Photos</h1>
      <h2>1000 words each. What are they saying to you?</h2>
    </hgroup>
  </header>
  {if $view}
    <div id="img{$image.id}" class="photo">
      <div class="grid-8">
          <ul>
            <li class="display" style="background-image: url('//cdn.joejiko.com/images/{$image.filename}_m.{$image.ext}'); width: {$image.width}px; height: {$image.height}px;">
              <div itemscope itemtype="http://schema.org/ImageObject">
                <h2 itemprop="name">{$image.caption}</h2>
                <img src="//cdn.joejiko.com/images/{image.filename}.{$image.ext}" itemprop="contentURL">
                by <span itemprop="author">Joe Jiko</span>
                {*
                 * Other fields to use
                  <span itemprop="contentLocation"></span>
                  <span itemprop="datePublished"></span>
                  <span itemprop="description"></span>
                *}
              </div>
            </li>
          </ul>
      </div>
      <aside class="grid-4">
        {if $image.children}
        <ul class="set">
          {foreach $image.children as $child}
          <li id="img{$child.id}" style="background-image: url('//cdn.joejiko.com/images/{$child.filename}_sq.{$child.ext}');"><a href="/p/{$child.id}" title="{$child.caption}"></a></li>
          {/foreach}
        </ul>
        {/if}

        {if $image.caption neq ''}
        <h2><em>"{$image.caption}"</em></h2>
        {/if}

        {if $category}
          <h2>{$category}</h2>
        {/if}

        {if $tags}
          <h3>{$tags}</h3>
        {/if}
      </aside>
    </div>
  {else}
    {if $images}
      {foreach $images as $image}
    <div id="img{$image.id}" class="photo" itemscope itemtype="http://schema.org/ImageObject">
      <div class="grid-8">
          <ul>
            <li class="display" style="background-image: url('//cdn.joejiko.com/images/{$image.filename}_m.{$image.ext}');">
              <a href="/p/{$image.id}" title="{$image.caption}"></a>
              <img src="//cdn.joejiko.com/images/{$image.filename}.{$image.ext}" itemprop="contentURL" hidden>
              <span itemprop="name" hidden>{$image.caption}</span>
              <div hidden>by <span itemprop="author" hidden>Joe Jiko</span></div>
            </li>
          </ul>
      </div>
      <aside class="grid-4">
        {if $image.children}
        <ul class="set">
          {foreach $image.children as $child}
          <li id="img{$child.id}" style="background-image: url('//cdn.joejiko.com/images/{$child.filename}_sq.{$child.ext}');" itemscope itemtype="http://schema.org/ImageObject">
            <img src="//cdn.joejiko.com/images/{$child.filename}.{$image.ext}" itemprop="contentURL" hidden>
          </li>
          {/foreach}
        </ul>
        {/if}

        {if $image.caption neq ''}
        <h2><em>"{$image.caption}"</em></h2>
        {/if}

        {if $category}
          <h2>{$category}</h2>
        {/if}

        {if $tags}
          <h3>{$tags}</h3>
        {/if}
      </aside>
    </div>
      {/foreach}
    {else}
    <div class="grid-8">
      <p>No images :/</p>
    </div>
    <aside class="grid-4">
      <!-- more stuff -->
    </aside>
    {/if}
    <footer class="grid-12">
      <!-- footer stuff -->
      <button>Load more...</button>
    </footer>
  {/if}
</article>