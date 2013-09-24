@extends('layouts.empty')
@section('content')

  @if (isset($error))
  <div class="ui-notice error">{{ $error }} </div>
  @endif

  <div class="ui-connect">
    <div class="buttons-grid expects-1">
      <h2>Sign in with email</h2>
      <form class="site-signin modal-form connect-email-form" method="post" action="/user/connect/email">
          <label class="modal-label connect-email-label" for="ucEmail">Email</label>
          <input name="email" type="email" class="modal-input connect-email-input" id="ucEmail">

          <label class="modal-label connect-email-label">Password</label>
          <input name="passwd" type="password" class="modal-input connect-email-input">
          <button class="btn-email-signin btn-green" type="submit">sign in</button>
          <label class="modal-label modal-link"><input type="checkbox" checked=""> Stay signed in</label>
      </form>
      <p>
        <a class="modal-link connect-email-link" href="/account/recover">Can't access your account?</a>
      </p>
    </div>
  </div>
@stop