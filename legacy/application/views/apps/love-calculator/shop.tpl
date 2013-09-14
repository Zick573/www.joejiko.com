<h3 class="highlight-red">You stroll up to the only shop in town</h3>
<p class="story">
You walk around the store and browse the selection of gifts. Checking your Bank Account, you see that you have <span class="highlight-red">{if $bank}${$bank.balance}</span>{/if} to spend. Your girl is expecting gifts and for you to take her out for dinner..
</p>
<p>Choose wisely. What was her <a href="/apps/love-calculator/hint">favorite gift</a> again?</p>

{if $message}
  <span style="background: yellow; padding: 5px;">{$message}</span>
{/if}

{if $items}
<ul class="shop">
  {foreach $items as $name => $item}
  <li>
    <form method="post" action="/apps/love-calculator/buy">
      <input type="hidden" name="{$name}" value="{$item.price}">
      <button class="action" type="submit">Choose</button>
      <span class="item">{$item.label} ${$item.price}</span> <span class="own">{$item.own}</span>
    </form>
  </li>
  {/foreach}
</ul>
{else}
  You don't have enough money to buy anything.. lol.
{/if}
<nav>
<a class="reset" href="/apps/love-calculator/start">Start over</a>
<a class="checkout" href="/apps/love-calculator/checkout">checkout</a>
</nav>