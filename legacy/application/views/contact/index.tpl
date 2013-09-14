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
    <p style="padding: 20px;">Disabled for now. You can find me on <a href="//jiko.us/WWbLpP">twitter</a> or <a href="//jiko.us/XHoGuc">google+</a>.</p>
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