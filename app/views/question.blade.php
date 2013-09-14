@extends('layouts.master')
@section('page.title')
  @if(isset($question))
    {{ $question->text }}
  @else
    Ask a stupid question, get a smart answer!
  @endif
@stop

@section('content')
  @if(isset($question))
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
  @else
  <h2>No data.</h2>
  @endif
@stop

@section('scripts.footer')
  <script>
    require(['questions'], function(Questions){
      Questions.start();
    });
  </script>
@stop