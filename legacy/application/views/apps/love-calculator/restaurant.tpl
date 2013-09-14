{if $message}
	<span style="background: yellow; padding: 5px;">{$message}</span>
{/if}
{if $locations}
	<h3 class="highlight-red">Choose your destiny..</h2>
	<form class="restaurants" method="post" action="/apps/love-calculator/dinner">
	{foreach $locations as $name => $values}
  	<button name="location" value="{$name}"><img src="/images/apps/love-calculator/{$values.img}.jpg">{$name}</button>
  {/foreach}
{/if}