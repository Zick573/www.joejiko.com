<div id="more-sidebar">
	 <div class="user-panel">
	 	<nav>
	 		{if $user}
	 			<a href="/user/settings"><i class="icon-user"></i> Logged in as {$user.name}</a>
	 			<a href="/team">Join the team</a>
	 			<a href="/user/logout">Logout</a>
	 		{else}
	  		<a data-action='login' href='/user/login'><i class="icon-user"></i>Login or sign up</a>
	  	{/if}
	  </nav>
	</div>
	<div class="search-panel">
	  <form id="search-form" action="/search" method="post">
	    <input id="more-sidebar-query" class="placeholder" type="text" placeholder="e.g. Zeah photos" name="query">
	    <button type="submit"><i class="icon-search"></i></button>
	  </form>
	 </div>
</div>