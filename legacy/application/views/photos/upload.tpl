<article id="page-upload">
  <header class="grid-12">
    <hgroup>
      <h1>Photos</h1>
      <h2>Upload</h2>
    </hgroup>
  </header>
  <div class="grid-8">
    {if $result}
      <p>File upload request processed..</p>
      <img src="{$result.name}.{$result.ext}">
      <img src="{$result.name}_l.{$result.ext}">
      <img src="{$result.name}_m.{$result.ext}">
      <img src="{$result.name}_s.{$result.ext}">
      <img src="{$result.name}_ss.{$result.ext}">
      <img src="{$result.name}_t.{$result.ext}">
    {else}
      {include file='./uploadform.tpl'}
    {/if}

    {if count($queue) > 0}
      <form action="/upload" method="post">
        <input type="hidden" name="import" value="1">
        <button type="submit">Import</button>
      </form>
    {/if}
  </div>
  <aside class="grid-4">
    <!-- more stuff -->
  </aside>
  <footer class="grid-12">
    <!-- footer stuff -->
  </footer>
</article>