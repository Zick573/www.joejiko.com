@extends('layouts.master')
@section('page.title')
  Send a message
@stop
@section('content')
  @if (isset($user) && $user->name !== "Anonymous")
  @if ($user->role)

  @endif
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
            {{ $user->name }}
          </span>
          &lt;<span class="sender-email" data-editable-name="sender[email]" editable><!--
            -->{{ $user->email }}<!--
          --></span>&gt;
        </h3>
      </div>
    </header>
    <div class="message-subject">
      <input class="message-subject-content" type="text" name="message[subject]" placeholder="Subject">
    </div>
    <div class="message-body">
      <textarea class="message-body-content" placeholder="Write a message.." name="message[body]"></textarea>
    </div>
    <footer class="message-footer">
      <a data-trigger="message.send" class="btn-green message-send" href="/contact/message/send">Send</a>
    </footer>
  </div>
  @else
  <h2>Oops..</h2>
  <p>You must sign in to send a message</p>
  <p>
    If you'd rather not sign in and still want to contact me.. you can contact me on one of my <a href="/contact/other">public profiles</a>
  </p>
  @endif
@stop