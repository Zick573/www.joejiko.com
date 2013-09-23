@extends('layouts.empty')
@section('content')

  @if (isset($error))
  <div class="ui-notice error">{{ $error }} </div>
  @endif

  <div class="ui-connect">
    <div class="buttons-grid expects-1">
      <h2>Sign in with email</h2>
      <form class="site-signin connect-email-form" method="post" action="/user/connect/email">
          <label class="connect-email-label" for="ucEmail">Email</label>
          <input name="email" type="email" class="connect-email-input" id="ucEmail">

          <label class="connect-email-label">Password</label>
          <input name="passwd" type="password" class="connect-email-input">
          <button class="btn-email-signin btn-green" type="submit">sign in</button>
          <label><input type="checkbox" checked=""> stay signed in</label>
      </form>
      <p>
        <a class="connect-email-link" href="/account/recover">Can't access your account?</a>
      </p>
    </div>
  </div>
@stop