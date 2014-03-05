<div class="user-module">
@if (!Auth::guest())
    <div class="user-status">
      <div class="control-triggers">
        @if (isset(Auth::user()->info->photo_url))
        <img class="user-status-photo" width="48" height="48" src="{{ Auth::user()->info->photo_url }}">
        @else
        <i class="batch" data-icon="&#xF046"></i>
        @endif

        <a class="user-status-link" href="/user"><strong>{{ Auth::user()->name }}</strong></a>

        @if (Auth::user()->isTeam())
        <a class="team-nav-link" href="/team">Team</a>
        @endif

        @if (Auth::user()->isAdmin())
        <a class="admin-nav-link" href="/admin">Admin</a>
        @endif
      </div>
    </div>
    <div class="user-controls">
      <a class="user-nav-link" href="/team/join">Join the team <i class="batch" data-icon="&#61509;"></i></a>
      <a class="user-nav-link" href="/thought/create">Post something <i class="batch" data-icon="&#61441;"></i></a>
      <a class="user-nav-link" href="/user/tools/twitter-archive">Twitter Archive</a>
      <a class="user-nav-link" href="/user/info">Update contact info <i class="batch" data-icon="&#61645;"></i></a>
      <a class="user-nav-link btn-disconnect" href="/user/disconnect">
        <i class="batch" data-icon="&#61545;"></i> Disconnect
      </a>
    </div>

    @if (Auth::user()->isTeam())
    <div class="team-controls"></div>
    @endif

    @if (Auth::user()->isAdmin())
    <div class="admin-controls"></div>
    @endif
@else
  Hello lurker!
  <a class="user-nav-link btn-purple" href="/team/join"><i class="batch" data-icon="&#61509;"></i> Join the team</a> or
  <div class="mod-connect">
    <a class="user-nav-link btn-green btn-user-connect" href="/user/connect"><i class="batch" data-icon="&#xF046;"></i> connect</a>
    <div class="connect-overlay">
      <p class="intro">Connect with Google, Twitter, or Facebook to gain access to special pages and features on JoeJiko.com</p>
      <a href="/user/connect/google" class="btn-connect-g"><i class="sign-in-google"></i> Google</a>
      <a href="/user/connect/facebook" class="btn-connect-f"><i class="sign-in-facebook"></i> Facebook</a>
      <a href="/user/connect/twitter" class="btn-connect-t"><i class="sign-in-twitter"></i> Twitter</a>
      <p class="privacy">
        Nothing will ever be posted to your accounts without your permission! This site does not automatically post.<br>
        <a href="/about/privacy">read the privacy policy</a>
      </p>
    </div>
  </div>
@endif
</div>