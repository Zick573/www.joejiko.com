<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
<title>
{if isset($meta.title) and $meta.title}
	{$meta.title|escape:'htmlall':'UTF-8'}
{/if}
</title>
{if isset($meta.description) AND $meta.description}
	<meta name="description" content="{$meta.description|escape:html:'UTF-8'}" />
{/if}
{if isset($meta.keywords) AND $meta.keywords}
	<meta name="keywords" content="{$meta.keywords|escape:html:'UTF-8'}" />
{/if}
<meta name="author" content="Joe Jiko <me@joejiko.com>" />
<meta name="copyright" content="Copyright 2009-2013 Joe 'Jiko' All rights reserved." />
<meta name="revisit-after" content="5 days" />
<meta name="robots" content="index,follow" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
<meta property="fb:admins" content="513086950" />
<meta property="fb:page_id" content="138247282858164" />
<meta property="fb:app_id" content="107650112611818" />
<meta property="og:title" content="{if isset($meta.title) and $meta.title}{$meta.title|escape:'htmlall':'UTF-8'}{/if} | Joe Jiko Apps"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://joejiko.com/"/>
<meta property="og:image" content="http://joejiko.com/images/logo.png"/>
<meta property="og:site_name" content="Joe Jiko"/>
<!-- styles -->
<link href='http://fonts.googleapis.com/css?family=Stalemate|Marvel:400,700' rel='stylesheet' type='text/css'>
<link href='/styles/gs/toast/grid.css' rel='stylesheet' type='text/css'>
{if isset($styles) AND $styles}
	{foreach $styles as $uri=>$media}
	 <link href="{$uri}" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach}
{/if}
<!-- scripts -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="/scripts/apps/twitter-manager/scripts.js"></script>
{if isset($scripts.head)}
	{foreach $scripts.head as $uri => $valid}
  	{if $valid}
			<script src="{$uri}"></script>
    {/if}
	{/foreach}
{/if}
</head>
  <body>
    <header id="header">
      <nav><a href="/"><span>←</span><span class="extended"> back to</span> joejiko.com</a></nav>
      <p class="highlight-red"><a href="/apps/twitter-manager">TwitterRM</a></p>
      <p class="branding"><a href="/"><img src="/images/logo.png" alt="by joe jiko" title="Jump to the front page"></a></p>
    </header>
    <div id="container">
      <div class="twrm-wrap">
        <div id="twitter-manager">
          {$method}
        </div>
      </div>
  	</div>
    <footer></footer>
    <script>
  <!-- asynchronous google analytics -->
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-4976857-2']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  	</script>
  </body>

</html>