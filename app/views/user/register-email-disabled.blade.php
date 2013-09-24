@extends('layouts.master')
@section('page.title')
  Account registration with email
@stop
@section('content')
  <h1>Oops!</h1>
  <p>Registration with email is currently disabled. <a href="https://twitter.com/intent/tweet?text={{ urlencode("hey @joejiko") }}&related=joejiko,jjcoms">Tweet me</a> to <a href="https://twitter.com/intent/tweet?text={{ urlencode("hey @joejiko I can't register for an account with my email and that makes me feel _____") }}&related=joejiko,jjcoms">let me know how inconvenient this is for you</a> or <a href="/user/connect">connect with your Google, Twitter, or Facebook account</a> instead.</p>
@stop