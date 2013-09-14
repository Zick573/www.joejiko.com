<article class="error">
	<hgroup>
    <h1>Uhh...</h1>
    <h2>You should probably tell someone about this.</h2>
  </hgroup>
  {if isset($debug)}
  <div>
      <pre>{$e|default:' '}</pre>
      <p>Reason: {$message|default:'unknown'}</p>
  </div>
  {/if}
</article>