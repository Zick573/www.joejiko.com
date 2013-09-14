<article id="page-contact">
  <header class="grid-12">
    <hgroup>
      <h1>Contact me</h1>
      <h2>Ya feelin' chatty?</h2>
    </hgroup>
    <nav class="tabs">
      <h2{if $type eq 'general' or not isset($type)}  class="active"{/if}><a href="/contact/general" title="General contact">General</a></h2>
      <h2{if $type eq 'new-project-request'} class="active"{/if}><a href="/contact/new-project-request" title="New project request">New project request</a></h2>
      <h2{if $type eq 'questions'} class="active"{/if}><a href="/contact/questions" title="Contact for questions">Questions</a></h2>
    </nav>
  </header>
<!--  <hgroup class="tabs grid-6"></hgroup> -->
  <form id="contact" class="contact grid-9">
    <ul class="tab-header">
    	<li{if $type eq 'general' or not isset($type)} class="default"{/if}>Just saying hello? Write your comments, compliments, or suggestions.</li>
      <li{if $type eq 'new-project-request'} class="default"{/if}>Currently not available for new projects <a href="/resume">View resume</a> *All fields are required</li>
    	<li{if $type eq 'questions'} class="default"{/if}>Have a quick question? Ask me anything!</li>
    </ul>
    <fieldset class="general{if $type eq 'general' or not isset($type)} default{/if}">
			{include file='./general.tpl'}
    </fieldset>
    <fieldset class="project{if $type eq 'new-project-request'} default{/if} masked">
			{include file='./project.tpl'}
    </fieldset>
    <fieldset class="questions{if $type eq 'questions'} default{/if}">
	    <div id="editable3" class="question-message message-body" contenteditable="true">
      	<strong>What? Who? When? Where? Why?</strong>
      </div>
    </fieldset>
    <fieldset class="user default">
      {if !$user}
      <div id="social-signin">
        <h3>or sign with</h3>
        <button class="g-button f-button-signin"><i></i>Facebook</button>
        <button class="g-button g-button-signin"><i></i>Google</button>
        <button class="g-button t-button-signin"><i></i>Twitter</button>
      </div>
      {/if}
      <h3>Identity <label><input id="anonymous" name="anonymous" type="checkbox" /> Send anonymously</label></h3>
      <div id="user-identity">
        {if $user}
        <input id="contact-id" name="id" type="hidden" value="{$user.id}" />
        <input id="contact-name" name="name" type="text" placeholder="name" value="{$user.name}" />
        <input id="contact-email" name="email" placeholder="email address" type="email" value="{$user.email}" />
        {else}
        <input id="contact-name" name="name" type="text" placeholder="name" />
        <input id="contact-email" name="email" placeholder="email address" type="email" />
        {/if}
      </div>
    </fieldset>
    <footer>
      <label class="option-subscribe">
        <input id="contact-optin" name="subscribe" type="checkbox" />
        Subscribe me to email updates
      </label>
      <button type="submit">send message &gt;</button>
    </footer>
  </form>
	<aside class="grid-3">
		{include file='./contact-sidebar.tpl'}
  </aside>
</article>
<script>
if (!Modernizr.inputtypes.date) {
  // no native support for <input type="date"> :(
  // maybe build one yourself with Dojo or jQueryUI
}
</script>
<script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.3/dojo/dojo.js" data-dojo-config="async: true"></script>