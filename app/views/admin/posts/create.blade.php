@extends('layouts.master')

@section('content')
  <h2>Create new post</h2>
  {{ Form::open(array('route' => 'admin.posts.store')) }}

  <div class="control-group">
    {{ Form::label('title', 'title') }}
    <div class="controls">
      {{ Form::text('title') }}
    </div>
  </div>

  <div class="control-group">
    {{ Form::label('body', 'content') }}
    <div class="controls">
      {{ Form::textarea('body') }}
    </div>
  </div>

  <div class="form-actions">
    {{ Form::submit('Save', array('class' => 'btn btn-success btn-save btn-large')) }}
    <a href="{{ URL::route('admin.posts.index') }}" class="btn btn-large">Cancel</a>
  </div>

  {{ Form::close() }}
@stop