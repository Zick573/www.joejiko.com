<article id="question-{$question.id}" class="question">
  <header>
    <hgroup>
      <h2 class="question-text"><a href="/question/{$question.id}">{$question.question}</a></h2>
      <h3 class="question-id">#{$question.id}</h3>
    </hgroup>
  </header>
	<p class="answer-text">{$question.answer}</p>
	<footer class="date"><time datetime="{$question.date}">{$question.date}</time></footer>
</article>