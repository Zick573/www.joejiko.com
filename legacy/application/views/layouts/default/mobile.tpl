<!DOCTYPE html>
<!--[if IEMobile 7 ]>    <html class="no-js iem7"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html class="no-js mobile"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
				<title>
				{if isset($meta.title) and $meta.title}
					{$meta.title|escape:'htmlall':'UTF-8'}
				{/if}
				</title>
        <meta name="description" content="">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="cleartype" content="on">

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/touch/apple-touch-icon-144x144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/touch/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/touch/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="img/touch/apple-touch-icon-57x57-precomposed.png">
        <link rel="shortcut icon" href="img/touch/apple-touch-icon.png">

        <!-- Tile icon for Win8 (144x144 + tile color) -->
        <meta name="msapplication-TileImage" content="img/touch/apple-touch-icon-144x144-precomposed.png">
        <meta name="msapplication-TileColor" content="#222222">
        <link rel="stylesheet" href="styles/layouts/default/mobile.css">
        <script src="scripts/libraries/modernizr/modernizr.js"></script>
    </head>
    <body>
    <header>
    	<nav>
    	   <a href="/blog">Blog</a>
           <a class="close" href="#">Close this</a>
        </nav>
        <hgroup>
    		<h1>Hi there!</h1>
    		<h2>I see you're on a mobile device.</h2>
    		<h3>Things might look weird while I set this up..</h3>
    		<h4>I suggest you go to the <a href="/blog">blog</a></h4>
    		<h5>(because seriously nothing works here)</h5>
        </hgroup>
		<p>oh, you can <a href="sms://+17273868288?body=Hi Joe">text me if you want</a></p>
    </header>
    <div role="main">
	{if !empty($action)}
		{$action}
	{/if}
    </div>
    {literal}
    <script src="scripts/libraries/jquery/jquery-1.7.2.min.js"></script>
    <script src="scripts/music/tracker.js?v=3"></script>
    {/literal}
    <script>
    {literal}
    $('nav .close').on('click', function(){
        $('body > header').fadeOut('fast');
        return false;
    });
  <!-- asynchronous google analytics -->
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-4976857-2']);
    _gaq.push(['_trackPageview']);
    _gaq.push(['_trackEvent', 'mobile', 'view', 'scrambled site', 1])

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
    {/literal}
		</script>
    </body>
</html>
