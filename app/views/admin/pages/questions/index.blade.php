@extends('layouts.admin.master')
@section('page.title')
  Manage questions
@stop
@section('page.styles')
<style>
  body { font-family: arial, sans-serif; }
  p { margin: 0; }
  .filters { background: #eee; padding: .25rem; }
  .question-text {
    font-weight: bold;
    border-bottom: 1px solid;
    line-height: 2;
  }
  .question-response { position: relative; padding-bottom: 2rem; }
  .question-response-text { width: 100%; height: 5rem; }
  .inline-item { display: inline-block;   }
  .response-controls { position: absolute; bottom: 0; left: 0; }
  .status-controls { position: absolute; bottom: 0; right: 0; }
</style>
@stop
@section('controls')
  <ul>
    <li>unanswered</li>
    <li>answered</li>
  </ul>
@stop
@section('content')
  <div class="filters">
    <label><input type="checkbox"> show ignored</label>
    <label><input type="checkbox"> show rejected</label>
  </div>
  <div class="questions-unanswered">
  @foreach($questions_unanswered as $question)
    <div class="question">
      <p class="question-text">Q: {{ $question->text }}</p>
      <div class="question-response">
        <form class="question-response-form" action="/admin/questions" method="post">
          <input type="hidden" name="id" value="{{ $question->id }}">
          <textarea class="question-response-text" name="response">{{ $question->response }}</textarea><br>
          <div class="response-controls">
            <button class="question-control-item submit-response" type="submit">submit response</button>
            <label class="inline-item"><input type="checkbox" checked>send to twitter</label>
          </div>
        </form>
        <form class="status-controls" action="/admin/questions" method="post">
          <input type="hidden" name="id" value="{{ $question->id }}">
          <select name="status" class="inline-item question-control-item">
            @foreach($questions_status as $status)
              @if($status->id == $question->status)
              <option value="{{ $status->id }}" selected>{{ $status->label }}</option>
              @else
              <option value="{{ $status->id }}">{{ $status->label }}</option>
              @endif
            @endforeach
          </select>
          <button type="submit">Update status</button>
        </form>
      </div>
    </div>
  @endforeach
    <div class="questions-ignored" hidden>

    </div>
    <div class="questions-rejected" hidden>

    </div>
  </div>
  <div class="questions-answered">
  @foreach($questions_answered as $question)
    <div class="question">
      <p class="question-content">
        Q: {{ $question->text }}<br>
        <form action="/admin/questions/update/{{ $question->id }}" method="post">
          A: <textarea name="response">{{ $question->response }}</textarea><br>
          <button type="submit">update response</button>
        </form>
      </p>
      <div class="controls">
        <form action="/admin/questions/update/{{ $question->id }}" method="post">
          <select name="status">
            @foreach($questions_status as $status)
              @if($status->id == $question->status)
              <option value="{{ $status->id }}" selected>{{ $status->label }}</option>
              @else
              <option value="{{ $status->id }}">{{ $status->label }}</option>
              @endif
            @endforeach
          </select>
          <button type="submit">Update status</button>
        </form>
      </div>
    </div>
  @endforeach
  </div>
@stop