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
<meta name="copyright" content="Copyright 2009-2012 Joe 'Jiko' All rights reserved." />
<meta name="revisit-after" content="5 days" />
<meta name="robots" content="index,follow" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

<meta property="fb:admins" content="513086950" />
<meta property="fb:page_id" content="138247282858164" />
<meta property="fb:app_id" content="107650112611818" />

<meta property="og:title" content="{if isset($meta.title) and $meta.title}{$meta.title|escape:'htmlall':'UTF-8'}{/if}"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://joejiko.com/"/>
<meta property="og:image" content="http://joejiko.com/images/logo.png"/>
<meta property="og:site_name" content="Joe Jiko"/>


<!-- global stylesheets -->
<!--google web fonts-->
<link href='http://fonts.googleapis.com/css?family=Stalemate|Marvel:400,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300,500,900,400italic' rel='stylesheet' type='text/css'>
<link href='/styles/gs/toast/grid.css' rel='stylesheet' type='text/css'>
{if isset($styles) AND $styles}
	{foreach $styles as $uri=>$media}
	<link href="{$uri}" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach}
{/if}

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
{if isset($scripts.head)}
	{foreach $scripts.head as $uri => $valid}
  	{if $valid}
			<script src="{$uri}"></script>
    {/if}
	{/foreach}
{/if}
</head>
	<body {if isset($page_name)}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if}>
	<!-- meebo bar -->
	{*include file='vendor/meebo/mbar-header.tpl'*}
  <!--
  	TO DO: IE warning? chrome frame?
    //-->
  {include file='./header.tpl'}
  <div id="container">
    <div class="clearfix">
    	<div class="grids">
      	{include file='./sidebar.tpl'}
        <div class="grid-10" role="main">
        	<div id="content">
		 	    {if isset($error)}<span class="error grid-12">{$error}</span>{/if}