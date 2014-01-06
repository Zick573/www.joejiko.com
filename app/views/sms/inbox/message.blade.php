<div class="sms-item">
  <div class="message">
    <time class="date-received">{{ date("M. d", strtotime($date_received)) }}</time>
    <div class="from">
      <i class="icon-from">➟</i> <strong class="from-name">{{ trim($from) ?: '???' }}</strong><br>
      <i class="icon-phone">✆</i> <em class="from-phone">{{ @$from_phone }}</em>
    </div>
    <p class="body">{{ $body }}</p>
  </div>
  <footer class="actions">
    {{ Form::open() }}
    {{ Form::hidden('reply_to', $reply_to) }}
    {{ Form::textarea('response', '', ['class' => 'col reply-text']) }}<!--
    --><button class='col btn-submit btn-reply' type="submit">
        <i class="icon-reply">➥</i>
        </button>
    {{ Form::close() }}
  </footer>
  {{-- var_dump($missing) --}}
</div>