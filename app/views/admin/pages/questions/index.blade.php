@extends('layouts.master')
@section('page.title')
  Manage questions
@stop
@section('content')
  @foreach($questions as $question)
    <p>
      Q: {{ $question->text }}<br>
      A: {{ $question->response }}<br>
      @if((int)$question->status === 0)
      <button data-qid="{{ $question->id }}" data-action="answer">answer</button>
      @else
      <div class="actions" data-qid="{{ $question->id}} ">
        <button data-qid="{{ $question->id }}" data-action="answer-edit">edit</button>
        <button data-qid="{{ $question->id }}" data-action="answer-hide">soft delete</button>
        <button data-qid="{{ $question->id }}" data-action="answer-delete">delete</button>
      @endif
    </p>
  @endforeach
@stop