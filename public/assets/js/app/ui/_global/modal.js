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
    // console.log("modal keypress..which: "+evt.which);

    // ESC (close all modals)
    if(27 == evt.keyCode) {
      destruct(true);
    }
  }

  function destruct_all() {
    var modals = $(document).find('.modal');
    modals.each(function(i, e){
      $(e).off('loaded');
      $(e).remove();
    });
  }

  // @todo unbind all events
  function destruct(evt, all) {
    if(!$modal) { return; }
    $modal.off('loaded');

    if(typeof(all) !== 'undefined') {
      destruct_all();
    } else {
      // kill the current modal
      $(this).parents('.modal').remove();
    }

    var modals = $(document).find('.modal'),
        modalCount = modals.length;

    // no modals left
    if(0 == modalCount) {
      $('body').removeClass('modal--active');
      $(document).off('keypress', modal_keypress_handler);
    }

    if(0 < modalCount) {
      modals.each(function(i, e){
        $(e).removeClass('modal-sub');
      });
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
      var modalCount = $(document).find('.modal').length;
      if(!$('body').hasClass('modal--active')) {
        $('body').addClass('modal--active');
      }

      // first modal on page
      if(0 == modalCount) {
        $(document).on('keypress', modal_keypress_handler);
      }

      // sub-modals
      if(modalCount > 0) {
        $(document).find('.modal').addClass('modal-sub');
      }

      // create the new modal
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