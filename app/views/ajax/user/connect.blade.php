@extends('layouts.empty')
@section('content')

  @if (isset($error))
  <div class="ui-notice error">{{ $error }} </div>
  @endif

  <div class="ui-connect">
    <div class="buttons-grid expects-1">
      <h2><em>Connect</em> with Google, Twitter, or Facebook to gain access to special pages and features on JoeJiko.com</h2>
      <div class="button-col">
        <a class="btn-connect-f" href="/user/connect/facebook"><i class="sign-in-facebook"></i> Connect with Facebook</a>
      </div><!--
      --><div class="button-col">
        <a class="btn-connect-g" href="/user/connect/google"><i class="sign-in-google"></i> Connect with Google</a>
      </div><!--
      --><div class="button-col">
        <a class="btn-connect-t" href="/user/connect/twitter"><i class="sign-in-twitter"></i> Connect with Twitter</a>
      </div>
      <p>Nothing will <em>ever</em> be posted to your accounts without your permission! This site does not automatically post. <a href="/about/privacy">read the privacy policy</a></p>
    </div>
    <aside class="connect-sidebar">
      <div class="user-alt-signup">
        <h3 class="alt-signup-title">Or</h3>
        <a class="btn-connect-site" href="/user/register/email" data-modal-open="user.register-email">Connect with Email</a>
      </div>
      <div class="existing-member">
        <p class="existing-member-message">Already connected to JoeJiko.com?</p>
        <a class="btn-connect-site" href="/user/connect/email" data-modal-open="user.connect-email">Sign in with your email</a>
    </aside>
  </div>
@stop