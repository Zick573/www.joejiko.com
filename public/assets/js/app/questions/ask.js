define(["jquery", "app/ui/_global/modal"], function($, Modal){

  var valid = false,
    options = {
      subscribe: false,
    },
    msg = {
      resp: "(this message will self-destruct..)"
    },
    // ui
    $subscribe, $anonymous, $submit, $modal, $question,
    $tooltip = $('<div class="ask-tooltip"></div>'),
    $tooltipList = $('<ul />');
  $tooltipList.append(
    $('<li><span>!</span> must be at least 15 characters</li>'),
    $('<li><span>!</span> No more than 255 characters</li>'),
    $('<li><span>!</span> must be in question form <em>ex. end in a question mark. no periods</em></li>')
  );
  $tooltipList.appendTo($tooltip);
  $tooltip.append($("<p>if you'd like to send me a message instead, visit the <a href='/contact'>contact page</a>"));

  function questionIsValid()
  {
    return this.valid;
  }

  function setValid(trueOrFalse)
  {
    if(typeof(trueOrFalse) !== "undefined") {

      this.valid = trueOrFalse;

      if(this.valid) {
        $modal.addClass('valid')
        return;
      }

      $modal.removeClass('valid')
      return;
    }

    this.valid = false;
  }

  function askCompleteModalCallback(context)
  {
    // @todo
  }

  function askModalCallback(context)
  {
    var jqxhr = $.get('/api/ui?name=questions/ask', function(html){
      context.empty().append(html);
    });
    jqxhr.done(function(){ context.trigger('loaded'); });

    $subscribe = $(document).find('[name=subscribe]'),
    $anonymous = $(document).find('[name=anonymous]'),
    $submit = $(document).find('[data-action=ask-submit]'),
    $modal = Modal.target,
    $question = $(document).find('[name=question]');

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

    $submit.on('click',function(evt){
      evt.preventDefault();

      var $this = $(this);

      if(!questionIsValid()){ $('.no').fadeIn('fast'); return false; }

      // submit
      $.post(
        "/questions/ask",
        {
          question: $("[name=question]").prop("value"),
          email: $("[name=email]").prop("value"),
          ask: true
        },
        function(html){

          // success
          var $message = $('<p />');
          $message.append(msg.resp);
          $modal.empty().append(html).append($message);

          // @todo ask another question
          // @todo resize modal?
          // @todo timed close?
      });
    });

    $question.on('change keyup', function()
    {
      validateCharacterCount($(this).prop('value'));
    });

    function displayCharacterCount(count)
    {

      var $chrDisplay = $(document).find('[data-label=character count]');

      // update character count display
      $chrDisplay.html( count );

      // colorize it
      if(chrcount > 15 && chrcount < 256)
      {
        $chrDisplay.css('color', 'green');
        return;
      }

      if(chrcount == 0)
      {
        $chrDisplay.css('color', '#ccc');
        return;
      }

      $chrDisplay.css('color', 'red');
    }

    function validateCharacterCount(text)
    {
      var count = text.length;
      // ends with ? and > 40 characters
      if (/^(.[^\.\?]*)\?$/.test($(this).prop('value')) && $(this).prop('value').length > 15) {
        // success!
        setValid(true);
        $(".no").hide('slow');
      }
      else
      {
        setValid(false);
      }

      // fade in UI
      $(".question-status").fadeIn('slow');
    }

    // help
    $(".help").on({
      "mouseenter": function(){
      $(this).append($tooltip);
      },
      "mouseleave": function(){
        $tooltip.remove();
      }
    });
  }

  function show(evt) {
    // show question modal
    evt.stopImmediatePropagation();
    Modal.open({url: 'questions/ask', callback: askModalCallback});
  }

  return {
    start: function() {
      $('[data-modal-name=ask]').on('click', show);
      console.log('ask functions ready');
    }
  };
});