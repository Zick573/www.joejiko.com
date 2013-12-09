@extends('layouts.master')
@section('page.title')
  Your user info
@stop
@section('content')
<article class="base-article">
<h1>User info</h1>
@if(isset($user))
  <p>
    You are <strong>{{ $user->name }}</strong><br>
    User #<strong>{{ $user->id }}</strong> Level <strong>{{ $user->role }}</strong><br>
    Contact email set to <strong>{{ $user->email }}</strong><br>
  </p>
  <p>
    That's all I know.
  </p>
@else
  You are {{ $user->name }}
@endif

<p>Would you like to <a class="btn btn-disconnect" href="/user/disconnect"><i class="batch" data-icon="&#61545;"></i> disconnect</a>?</p>
</article>
@stop