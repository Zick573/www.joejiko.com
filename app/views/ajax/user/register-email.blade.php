@extends('layouts.empty')
@section('content')

  @if (isset($error))
  <div class="ui-notice error">{{ $error }} </div>
  @endif

  <div class="ui-connect">
    <div class="buttons-grid expects-1">
      <h2>Create an Account</h2>
      <form class="site-signin modal-form" method="post" action="/user/register/email">
          <label class="modal-label" for="regName">Name</label>
          <input name="name" type="text" class="modal-input" id="regName">

          <label class="modal-label" for="regEmail">Email</label>
          <input name="email" type="email" class="modal-input" id="regEmail">

          <p>
            <label class="modal-label">Password*</label><br>
            <em>
              A password will be created for you. You'll be able to change it once you've confirmed your email address.
            </em>
          </p>

          <button class="btn-email-signin btn-green" type="submit">sign in</button>
          <!-- follow -->
          <label class="modal-label">
            <input type="checkbox" checked="">
              <a class="modal-link" href="/about/follow" data-modal-open="site.follow">Follow site updates</a>
          </label>
          <label class="modal-label modal-link">
            <input type="checkbox" checked=""> Stay signed in
          </label>
      </form>
      <p>
        <a class="modal-link" href="/user/connect">Want to connect with a Google, Twitter, or Facebook account instead?</a>
      </p>
    </div>
  </div>
@stop