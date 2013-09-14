{{ Form::open(array('url' => 'subscribe')) }}
{{ Form::email('email') }}
{{ Form::submit('subscribe') }}
{{ Form::close() }}