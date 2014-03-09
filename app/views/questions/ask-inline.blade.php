<div class="modal-ask modal-inline">
  <form action="/questions/ask" class="form-ask-inline form-ask ask">
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
  <div class="sending-msg" hidden>
    <h2>Wait a bit.. I'm sending your question.</h2>
  </div>
  <div class="sent-msg" hidden>
  @if(Auth::guest())
  {{-- Identity and subscription options --}}
  @include('questions.ask.sent-anon')
  @else
  {{-- Thanks for submitting --}}
  @include('questons.ask.sent-from-user')
  @include('questions.ask.thanks')
  @endif
  </div>
</div>