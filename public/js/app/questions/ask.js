define(["jquery", "app/ui/_global/modal"], function($, Modal){

  function handleQuestionSubmit(evt){
    var context = $(this).parents('.modal-ask');
    var $txtQuestion = context.find('.question-textarea');

    if(!questionIsValid($txtQuestion)){
      $('.no').fadeIn('fast');
      return false;
    }

    // hold a sec..
    context.addClass('modal--sending');

    // submit
    var jqxhr = $.post(
      "/questions/ask",
      {
        question: $txtQuestion.prop("value"),
        ask: true
      },
      function(html){
        // @todo ask another question
        context.removeClass('modal--sending')
          .addClass('modal--sent');
        // @todo resize modal?
        // @todo timed close?
    });
    jqxhr.error(function(err){
      context.removeClass('modal--sending');
      alert(err);
    });

    evt.preventDefault();
    // bubbly
    evt.stopImmediatePropagation();
  }

  function handleQuestionEntry(evt)
  {
    displayCharacterCount($(this).prop('value').length);
    questionIsValid($(this));
    evt.stopPropagation();
  }

  // binding
  $(document).on('keyup change', '.modal-ask .question-textarea', handleQuestionEntry);
  $(document).on('click', '.modal-ask .btn-question-submit', handleQuestionSubmit);

  function isValid(text)
  {
    // ends with ? and > 40 characters
    if (/^(.[^\.\?]*)\?$/.test(text) && text.length > 15) {
      return true;
    }

    return false;
  }

  function displayCharacterCount(count)
  {
    var display = $(document).find('.character-count');

    // update character count display
    display.html( count );

    // colorize it
    if(15 < count && 256 > count){
      display.css('color', 'green');
      return;
    }

    if(0 == count){
      display.css('color', '#ccc');
      return;
    }

    display.css('color', 'red');
  }

  function questionIsValid(context) {
    $parent = context.parents('.ask');

    if(!isValid(context.prop('value'))){
      $parent.removeClass('ask--valid');
      return false;
    }
    $parent.addClass('ask--valid');
    $(".no").hide('slow');
    return true;
  }

  function askModalCallback(context)
  {
    var $txtQuestion, $btnQuestionSend;
    var jqxhr = $.get('/api/ui?name=questions/ask', function(html){
      context.empty().append(html);
    });
    jqxhr.done(function(){
      context.trigger('loaded');
    });
  }

  function askCompleteModalCallback(context)
  {
    // @todo
    $subscribe = context.find('[name=subscribe]'),
    $anonymous = context.find('[name=anonymous]');
    $subscribe.on('click', function(evt){
      var $this = $(this);
      options.subscribe = $this.is('checked');
      $this.parent().find("div").removeAttr('hidden').fadeIn('slow');
      // @todo resize modal?
      evt.preventDefault();
    });

    $anonymous.on('change',function(evt){
      var $this = $(this);
      $this.parent().find("div").removeAttr('hidden').fadeIn('slow');
      // @todo resize modal?
    });
  }

  function show(evt) {
    // show question modal
    evt.stopImmediatePropagation();
    Modal.open({
      url: 'questions/ask',
      callback: askModalCallback,
      className: 'modal-ask'
    });
  }

  return {
    start: function() {
      $('[data-modal-name=ask]').on('click', show);
      console.log('ask functions ready');
    }
  };
});