@extends('layouts.master')
@section('page.title')
  Make it so, #1
@stop
@section('content')
  <div class="thought-post">
    {{ Form::open(array('url' => 'thought/create')) }}
    {{ Form::textarea('post_content', null, array('class' => 'thought-text')) }}
    <footer class="thought-footer">
      <em>post to</em>
      {{ Form::checkbox('post_to_google') }}
      {{ Form::label('post_to_google', 'google') }}
      {{ Form::checkbox('post_to_facebook') }}
      {{ Form::label('post_to_facebook', 'facebook') }}
      {{ Form::checkbox('post_to_twitter') }}
      {{ Form::label('post_to_twitter', 'twitter') }}
      <button class="btn-submit" type="submit">make it real!</button>
    </footer>
    {{ Form::close() }}
  </div>
@stop