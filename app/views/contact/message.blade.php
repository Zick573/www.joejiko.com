<div class="contact-message-wrap">

  <form name="message" action="/contact/message">
    <div class="contact-message">
      <header class="message-header">
        <h2 class="message-title">New Message</h2>
        <div class="message-recipients">
          <h3 class="message-to"><span class="message-label">To</span> Joe Jiko</h3>
          <input name="sender[name]" type="hidden" value="{{ $user->name }}">
          <input name="sender[email]" type="hidden" value="{{ $user->email }}">
          <h3 class="message-from">
            <span class="message-label">From</span>
            <span class="sender-name" data-editable-name="sender[name]" editable>
              {{ Auth::user()->name }}
            </span>
            &lt;<span class="sender-email" data-editable-name="sender[email]" editable><!--
              -->{{ Auth::user()->email }}<!--
            --></span>&gt; <small><em>&larr; this is you</em></small>
          </h3>
        </div>
      </header>
      <div class="message-subject">
        <input class="message-subject-content" type="text" name="message[subject]" placeholder="Subject (type your subject here)">
      </div>
      <div class="message-body">
        <textarea class="message-body-content" placeholder="Write a message.." name="message[body]"></textarea>
      </div>
      <footer class="message-footer">
        <button data-trigger="message.send" class="btn-green message-send" href="/contact/message/send" type="submit">
          Send
        </a>
      </footer>
    </div>
  </form>

  <div class="mask"></div>
  <div class="message">Sending your message..</div>
</div>