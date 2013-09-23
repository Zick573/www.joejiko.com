@extends('layouts.empty')
@section('content.1')
  <img src="/images/questions/ask/no.png" alt="no" />
@stop
@section('content')
  <form action="/questions/ask" class="ask ask--valid">
    <header class="ask-header">
      <h2 class="ask-h">Ask me anything</h2>
      <h3 class="ask-h">Go on.. anything..</h3>
      <aside class="help">
        <div class="help-intro">hover here to read the rules &mdash; <i class="modernpics" data-icon="?"></i></div>
        <div class="ask-tooltip">
            <ul class="help-list">
                <li class="help-list-item">
                  <i class="modernpics" data-icon="!"></i>
                  must be at least 15 characters
                </li>
                <li class="help-list-item">
                  <i class="modernpics" data-icon="!"></i>
                  No more than 255 characters
                </li>
                <li class="help-list-item">
                  <i class="modernpics" data-icon="!"></i>
                  must be in question form <em>ex. end in a question mark. no periods</em>
                </li>
            </ul>
            <p class="ask-tooltip-p">
              if you'd like to send me a message instead, visit the <a href='/contact'>contact page</a>
            </p>
        </div>
      </aside>
    </header>
    <div class="question-wrap">
      <div class="question-valid">
        <i class="bad modernpics" data-icon="👎"></i>
        <i class="good modernpics" data-icon="👍"></i>
      </div>
      <textarea class="question-text" name="question" rows="9" cols="40" placeholder="ask away...."></textarea>
      <i class="character-count">0</i>
    </div>
    <section class="question-author-info">
      <div class="question-section">
        <legend>Who are you?</legend>
        <div class="checkbox-group">
          <input class="anonymous" name="anonymous" type="radio" value="yes" checked="checked" />
          <label for="make-me-anonymous" class="checkbox-label">I'm nobody.. (anonymous coward)</label>
        </div>
        <div class="checkbox-group">
          <input class="identity" id="identity" name="anonymous" type="radio" value="no" />
          <label for="anonymous" class="checkbox-label">I'd like to reveal my identity..</label>
        </div>
      </div>
    </section>
    <section class="question-subscribe">
      <fieldset>
        <input class="notify" id="notify-by-email" name="notify_me" type="checkbox" value="1" />
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
      <img src="{{ cdn_img() }}pages/questions/ask/no.png" alt="no" />
      <div class="stops">
        <i class="modernpics" data-icon="&#xE7B3;"></i>
        <div style="display: none;"><div class="stop-1"></div><div class="stop-2"></div><div class="stop-3"></div></div>
      </div>
    </div>
  </form>
@stop