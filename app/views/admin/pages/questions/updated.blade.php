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
  <h2>Questions</h2>
  <ul>
    <li>unanswered</li>
    <li>answered</li>
  </ul>
@stop
@section('content')
  @if($result)
  Success!
  @else
  Fail!
  @endif

  <a href="/admin/questions">Go back to questions</a>
@stop