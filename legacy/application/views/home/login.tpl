<article class="user-login">
  <header class="grid-12">
    <hgroup>
      <h1>Hello!</h1>
    {if $user}
      <h2>Logged in as {$user.name}</h2>
    {else}
      <h2>Uhh... whoever you are.</h2>
    {/if}
    </hgroup>
  </header>
  <article class="grid-8">
  {if $user}
    <h2>You're logged in already!</h2>
    <p>Wanna <a href="/user/logout">logout</a>?</p>
  {else}
  	<h2>Login or sign up</h2>
  	<!-- http://www.googleplusdaily.com/2013/03/how-sign-in-with-google-is-different.html -->
    <div class="btn-wrap">
  		<button data-login='facebook' class="signin-f"><i class="sign-in-facebook"></i> Facebook</button>
  		<button data-login='google' class="signin-g"><i class="sign-in-google"></i> Google</button>
  		<button data-login='twitter' class="signin-t"><i class="sign-in-twitter"></i> Twitter</button>
    </div>
  {/if}

  {if $controller.continue}
  <p>
    Redirect after: {$controller.continue}
  </p>
  {else}
  <p>No redirect.</p>
  {/if}
  </article>
  <aside class="grid-4">
  	<h3>Why do I use social sign ins?</h3>
  	<p>Because I <em>hate</em> signing up for things &amp; having to remember passwords for sites I'm only going to visit once. I'm guessing you do too.</p>
  	<p><br>But you're going to come back more than once, right?</p>
  	<p>&larr; OK good. Eyes front</p>
  </aside>
</article>