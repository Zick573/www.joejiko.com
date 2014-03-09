define(["jquery"], function($){
  var _appui = {
    app: $('.app'),
    mainview: $('.main-view'),
    sidebar: $('.site-sidebar')
  },
  $sidebar = _appui.sidebar,
  isVisible = false,
  isReady = false;

  function getVisibility(){
    return this.isVisible;
  }

  function setVisibility(showOrHide) {
    this.isVisible = showOrHide;
  }

  function active() {
    $.event.trigger('sidebar.active')
    if(!getVisibility()) {
      $.event.trigger('sidebar.timer.started');
      this.timer = window.setTimeout(show, 500);
    }
  }

  function show() {
    if(getVisibility()) {
      hide();
      return;
    }

    sizing();
    $.event.trigger({type: 'sidebar.show'});
    $sidebar.css({
      "width": $sidebar.height(),
      "z-index": "999"
    });
    _appui.app.addClass('site-sidebar--focus');
  }

  function sizing() {
    $.event.trigger({type: 'sidebar.sizing'});
    setReady();
  }

  function setReady(){
    this.isReady = true;
    _appui.sidebar.addClass('loaded');
  }

  function hide() {
    $.event.trigger({type: 'sidebar.hide'});
    $sidebar.css({
      "width": "",
      "z-index": ""
    });
    _appui.app.removeClass('site-sidebar--focus');
  }

  $sidebar.on({
    mouseenter: show,
    // mouseenter: active,
    mouseleave: hide
  });

  return {
    start: function() {
      sizing();
    }
  };
});