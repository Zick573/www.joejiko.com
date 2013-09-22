define(["jquery"], function($){
  var
  $content,
  $modal,
  defaults = {};

  function trigger(event, callback)
  {
    $(document).trigger(event);
    // $events.trigger(event);
    if($.isFunction(callback)) {
      callback.call(element);
    };
  }

  function modal_keypress_handler(evt)
  {
    console.log("modal keypress..which: "+evt.which);
    if(0 == evt.which) {
      destruct();
      destruct_all();
    }
  }

  function destruct_all() {
    $(document).find('.modal').remove();
  }

  function destruct() {
    if(!$modal) { return; }
    $modal.off('loaded');
    // @todo unbind all events
    $(this).parents('.modal').remove();
    if(!$(document).find('.modal').length > -1) {
      $('body').removeClass('modal--active');
      $(document).off('keypress', modal_keypress_handler);
    }
  }

  function loaded(evt) {
    var context = $(this).parents('.modal');
    if(!context.hasClass('modal--loading')) return;
    context.removeClass('modal--loading');
  }

  return {
    close: function() {
      destruct();
    },

    open: function(params) {
      var data = null;
      if($(document).find('.modal').length > -1) {
        if(!$('body').hasClass('modal--active')) {
          $('body').addClass('modal--active');
        }
        $(document).on('keypress', modal_keypress_handler);
      }
      $modal = $('<div class="modal modal--loading" />');
      $modal.append(
        $('<div class="modal-positioning"><div class="modal-content-wrap"><div class="modal-close"><span class="modal-close-message"></span><i class="modernpics modal-close-x" data-icon="✕"></i></div><div class="modal-content"><div class="loading"><span class="loading-message">loading content..</span></div></div></div></div>'),
        $('<div class="modal-mask"></div>')
      );
      $content = $modal.find('.modal-content');
      $content.on('loaded', loaded);
      console.log("@modal open with: "+JSON.stringify(params));
      $modal.appendTo('body');
      $modal.find('.modal-close').on('click', destruct);
      $modal.find('.modal-mask').on('click', destruct);

      if(typeof(params.className) !== "undefined"){
        $modal.addClass(params.className);
      }

      if(typeof(params.options) !== "undefined") {

      }

      if(typeof(params.html) !== "undefined") {
        $content.empty().append(params.html);
        this.loaded($content);
        return;
      }

      if(typeof(params.data) !== "undefined") {
        data = params.data;
      }

      if(typeof(params.callback) !== "undefined") {
        params.callback($content, data);
      }

      return $modal;
    }
  };
});