<h3 class="highlight-red">After a crazy, intense day of shopping..</h3>
{if $message}<p class="story">{$message}</p>{/if}
<p>
{if $items}
	<ul class="items">
  {foreach $items as $name => $item}
    {if $item.own > 0}
    <li>
    	<img alt="{$name}" src="/images/apps/love-calculator/{$name}.jpg">
      {if $item.own > 1 and $item.plural}
        <strong class="highlight-red">{$item.own}</strong> {$item.plural}
      {else}
        <strong class="highlight-red">{$item.own}</strong> {$item.label}
      {/if}
    </li>
    {/if}
  {/foreach}
	</ul>
{/if}
</p>
<p>
{if $bank and $bank.balance < 0}
   Now you're broke! You owe your mom..
{/if}
</p>
<p>Ok cool. You <a class="action" href="/apps/love-calculator/restaurant">ready for dinner?</a></p>
