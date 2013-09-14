<h3>DUMP ALL DATA</h3>
{if $V}
	{$V|var_dump}
{else}
	$V not set
{/if}