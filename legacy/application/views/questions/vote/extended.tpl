<article id="questions" class="vote">
  <header class="grid-12">
    <hgroup>
      <h1><a href="/questions/vote">Vote</a></h1>
    </hgroup>
  </header>
  <div class="grid-8">
    <form method="post" action="/questions/vote">
      <h2>You selected 'other'</h2>
      <input type="hidden" name="sex" value="{$sex}">
      <label for="other-gift">What's your favorite V-day gift?</label>
      <input type="text" name="other-gift" id="other-gift">
      <button type="submit">Send vote</button>
    </form>    
  </div>
</article>