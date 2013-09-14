<h2>Choose your destiny..</h2>
{if $locations}
	<form method="post" action="/apps/love-calculator/dinner">
	{foreach $locations as $name => $values}
  	<button value="{$name}">{$name}</button>
  {/foreach}
  </form>
{/if}