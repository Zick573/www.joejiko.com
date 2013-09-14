<article id="questions" class="vote thanks">
  <header class="grid-12">
    <hgroup>
      <h1><a href="/questions/vote">Vote</a></h1>
    </hgroup>
  </header>
  <div class="grid-8">
  	<h2>Thanks!</h2>
    <p>I'll put this to good use.</p>
    
    <p>Would you like to be notifed of the results?</p>
    <p>Enter your email and click "notify me"</p>
    <form action="/questions/vote" method="post">
    	<input type="hidden" name="notify" value="1">
    	<input type="email" name="email" placeholder="you@example.com"><br>
			<button type="submit">notify me</button>
    </form>
  </div>
</article>