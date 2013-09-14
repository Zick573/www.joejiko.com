{if $message}
	Message: {$message}
{/if}
{if $bank}
	Bank: ${$bank.balance}
{/if}
{if $result}
<!-- result -->
<h3>After a crazy, intense day of shopping..</h3>
<p>You've bought: --cycle items bought-- if broke, display owe mom message --</p>
{else}
<h3>Market prices for common gifts</h3>
  {if $items}
  <ul>
    {foreach $items as $name => $item}
    <li>
      <form method="post" action="/apps/love-calculator/buy">
        <input type="hidden" name="{$name}" value="{$item.price}">
        {$name} ${$item.price}
        <button>Buy</button>
      </form>
    </li>
    {/foreach}
  </ul>
  {else}
  	You don't have enough money to buy anything.. lol.
  {/if}
  
  <a href="/apps/love-calculator">Start over</a> &mdash; 
  <form method="post" action="/apps/love-calculator/checkout"><button>I'm done shopping</button></form>
  
{/if}