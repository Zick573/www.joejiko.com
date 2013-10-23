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
        <a class="team-nav-link" href="/team">Team</a>
        <a class="admin-nav-link" href="/admin">Admin</a>
      </div>
    </div>
    <div class="user-controls">
      <a class="user-nav-link" href="/team/join">Join the team</a>
      <a class="user-nav-link" href="/user/info">Update contact info</a>
      <a class="user-nav-link" href="/thought/create">Post something</a>
      <a class="user-nav-link btn-disconnect" href="/user/disconnect">Disconnect</a>
    </div>

    @if (Auth::user()->isTeam())
    <div class="team-controls"></div>
    @endif

    @if (Auth::user()->isAdmin())
    <div class="admin-controls"></div>
    @endif
@else
  <a class="user-nav-link btn-green btn-user-connect" href="/user/connect"><i class="batch" data-icon="&#xF046;"></i> sign in</a>
@endif
</div>