<ul>
{if $tracks}
  {foreach $tracks as $track}
    {if $track.type eq 'now playing'}
      {include file='./now-playing.tpl'}
    {else}
      {include file='./track.tpl'}
    {/if}
  {/foreach}
{else}
<li class="warning">No iz tracks</li>
{/if}
</ul>