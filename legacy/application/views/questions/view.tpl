{if isset($ask)}
<script>
{literal}
$(window).load(function(){
  $('.ask a').trigger('click');
});
{/literal}
</script>
{/if}
<article id="page-questions">
  <header class="grid-12">
    <hgroup>
      <h1>Questions</h1>
      <h2>Ask a stupid question, get a smart answer!</h2>
    </hgroup>
  </header>
  <div class="grid-8">
    {include file='./question.tpl'}
  </div>
  <aside class="ask grid-4">
    <a href="/questions/ask" class="questions ask button">Ask a question<i class="mp-icon">?</i></a>
    <div id="clickthis"><span class="arrowhead">ˆ</span><span class="arrowshaft">,</span><span class="clicktext">click this</span></div>
    <!--<a href="/questions/vote">VOTE: What's your favorite Valentines Day gift?</a>-->
  </aside>
</article>