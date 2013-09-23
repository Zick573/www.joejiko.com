@extends('layouts.master')
@section('page.title')
  Connect or sign up
@stop
@section('content')

  @if (isset($error))
  <div class="ui-notice error">{{ $error }} </div>
  @endif

  <div class="ui-connect">
    <div class="buttons-grid expects-3">
      <h2>Sign in with</h2>
      <div class="button-col">
        <a class="btn-connect-f" href="/user/connect/facebook"><i class="sign-in-facebook"></i> Facebook</a>
      </div><!--
      --><div class="button-col">
        <a class="btn-connect-g" href="/user/connect/google"><i class="sign-in-google"></i> Google</a>
      </div><!--
      --><div class="button-col">
        <a class="btn-connect-t" href="/user/connect/twitter"><i class="sign-in-twitter"></i> Twitter</a>
      </div>
    </div><!--
    --><aside class="connect-sidebar">
      <div>
        <h3 class="use-site-credentials">Or use your site credentials</h3>
        <form action="/user/connect/email" method="post">
        <label class="lbl-connect">Email</label><!--
        --><input class="input-connect" type="email" name="email">
        <label class="lbl-connect">Password</label><!--
        --><input class="input-connect" type="password" name="passwd">
        <button class="btn-connect-site">Sign in</button>
      </div>
    </aside>
  </div>
@stop