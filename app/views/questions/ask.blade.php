@extends('layouts.empty')
@section('content')
  <form action="/questions/ask" class="ask">
    <header class="ask-header">
      <h2 class="ask-h">Ask me anything</h2>
      @include('questions.ask.tooltip')
    </header>
    <div class="question-wrap">
      <div class="question-valid">
        <i class="bad modernpics" data-icon="👎"></i>
        <i class="good modernpics" data-icon="👍"></i>
      </div>
      <textarea class="question-textarea" name="question" rows="9" cols="40" placeholder="ask away.... read the rules first"></textarea>
      <i class="character-count">0</i>
      <button class="btn-modal btn-question-submit" type="submit">send question</button>
      <div class="no">
        <span class="no-message">NO</span>
        <img class="no-img" src="{{ cdn_img() }}pages/questions/ask/no-shadow.png" alt="no" />
        <div class="stops">
          <i class="modernpics" data-icon="&#xE7B3;"></i>
        </div>
      </div>
    </div>
  </form>
  {{-- Step 2: Identity and subscription options --}}
  @include('questions.ask.after')

  {{-- Final: Thanks for submitting --}}
  @include('questions.ask.thanks')
@stop