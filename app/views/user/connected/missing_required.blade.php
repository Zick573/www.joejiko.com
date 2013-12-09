@extends('layouts.master')
@section('content')
<h1>You're connected!</h1>
<h2>Oh there is one more thing..</h2>
<p>Your email address is missing. So if you could just enter it, that would be great!</p>
<p><em>(I'll also need you to come in to work on Saturday... yeah.)</em></p>
{{ Form::open(array('url' => 'user/connected')) }}
{{ Form::label('email', 'Email address') }};
{{ Form::text('email') }}
{{ Form::close() }}
@stop