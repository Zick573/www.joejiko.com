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
      <a class="user-nav-link" href="/user/info">Update contact info <i class="batch" data-icon="&#61645;"></i></a>
      <a class="user-nav-link" href="/thought/create">Post something <i class="batch" data-icon="&#61441;"></i></a>
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
  <a class="user-nav-link btn-green btn-user-connect" href="/user/connect"><i class="batch" data-icon="&#xF046;"></i> connect</a>
@endif
</div>