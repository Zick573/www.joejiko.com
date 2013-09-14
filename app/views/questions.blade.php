@extends('layouts.master')
@section('page.title')
  Ask a stupid question, get a smart answer!
@stop

@section('content')
  <header class="ask-question">
    <a class="btn-ask" data-trigger="modal" data-modal-name="ask">Ask a question</a>
  </header>
  @foreach($questions as $question)
  <article itemscope itemtype="http://schema.org/Article" class="question" data-question-id="{{ $question->id }}">
    <meta itemprop="articleSection" content="Questions">
    <header class="question-header">
      <h2 itemprop="headline" class="question-text">
        <a href="/question/{{ $question->id }}">
          {{ $question->text }}
        </a>
      </h2>
      <div class="question-info">
        <h3 class="question-id">
          #{{ $question->id }}
        </h3>
        <h4 class="question-asked-by">
          asked by
          <span itemprop="contributor">{{ $question->guest_name }}</span>
        </h4>
      </div>
    </header>
    <p itemprop="articleBody" class="response-text">
      {{ $question->response }}
    </p>
    <footer class="time-data">
      <time itemprop="dateCreated" datetime="{{ $question->created_at }}">
        {{ $question->created_at }}
      </time>
      <time itemprop="dataPublished" datetime="{{ $question->response_created_at }}">
        {{ $question->response_created_at }}
      </time>
      <time itemprop="dataModified" datetime="{{ $question->updated_at }}">
        {{ $question->updated_at }}
      </time>
    </footer>
  </article>
  @endforeach
@stop

@section('scripts.footer')
  <script src="/assets/js/app/questions/app.js" async="true"></script>
@stop