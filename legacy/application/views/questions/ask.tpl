{if isset($status) and $status eq '200'}
	{include file='./ask/thank-you.tpl'}
{else}
<form action="/questions/ask" id="question-ask">
  <header>
    <hgroup>
      <h2>Ask me anything</h2>
      <h3>Go on.. anything..</h3>
      <h3 class="help">hover here to read the rules &mdash; <span>?</span></h3>
    </hgroup>
  </header>
  <div class="question-wrap">
    <div class="question-status">
      <span class="bad">L</span>
      <span class="good">l</span>
    </div>
    <textarea id="question-body" name="question" rows="9" cols="40" placeholder="ask away...."></textarea>
    <span class="character-count">0</span>
  </div>
	<section id="question-author-info">
    <fieldset>
      <legend>Who are you?</legend>
      <input id="make-me-anonymous" name="make_me_anonymous" type="radio" value="1" checked="checked" />
      <label for="make-me-anonymous">I'm nobody.. (anonymous coward)</label>
      <input id="make-me-known" name="make_me_anonymous" type="radio" value="0" />
      <label for="make-me-known">I'd like to reveal my identity..</label>
      <div hidden>
        <em>This option isn't available yet ;)</em>
      </div>
    </fieldset>
	</section>
  <section id="question-subscribe">
    <fieldset>
      <input id="notify-by-email" name="notify_me" type="checkbox" value="1" />
      <label for="notify-by-email">notify me automatically by email when my question has a response</label>
      <div hidden>
        <em>Thanks for wanting to be involved! I haven't set this up yet, but if you put your email in here, I'll let you know when it's ready.</em>
        <label for="question-author-email">email</label>
        <input id="question-author-email" name="email" type="email">
      </div>
    </fieldset>
  </section>
  <button type="submit">send question</button>
  <div class="no">
  	<span>NO</span>
    <img src="/images/questions/ask/no.png" alt="no" />
    <div class="stops">
    	<i style="font-family: modernpics; font-size: 10em;">'</i>
    	<div style="display: none;"><div class="stop-1"></div><div class="stop-2"></div><div class="stop-3"></div></div>
    </div>
  </div>
</form>
{/if}