@extends('layouts.master')
@section('page.title')
  User &mdash; Info
@stop
@section('content')
<article class="base-article">
  <div class="user-info-contact">
    <div class="user-info-section">
      <span class="user-info-label">Name</span>
      <input class="user-info-input" type="text" value="{{ $user->name }}" name="user[name]">
    </div>
    <div class="user-info-section">
      <span class="user-info-label">Email</span>
      <input class="user-info-input" type="email" value="{{ $user->email }}" name="user[email]">
    </div>
    <a data-trigger="user.info.update" class="btn-green user-info-save" href="/user/info/save">Save</a>
  </div>
<!--   <div class="user-info-connected">
    <span class="user-info-label">Connected profiles</span>
    <p>???</p>
  </div> -->
</article>
@stop