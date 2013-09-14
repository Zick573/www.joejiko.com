@extends('layouts.master')
@section('page.title')
  @if (isset($user->role) && $user->role <= 3)
    Welcome Jikosian! (Team Jiko)
  @else
    Not allowed!
  @endif
@stop
@section('content')
  @if (isset($user->role) && $user->role <= 3)
    You can see this because you're part of the team!
  @else
    Team only!
  @endif
@stop