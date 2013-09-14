define(["jquery"], function($){
  var _appui = {
    mainview: $('.main-view'),
    footer: $('.site-footer')
  },
  $footer = _appui.footer,
  isVisible = false,
  isReady = false;

  function getVisibility(){
    return this.isVisible;
  }

  function setVisibility(showOrHide) {
    this.isVisible = showOrHide;
  }

  function active() {
    $.event.trigger('footer.active')
    if(!getVisibility()) {
      $.event.trigger('footer.timer.started');
      this.timer = window.setTimeout(show, 1000);
    }
  }

  function show() {
    if(getVisibility()) {
      hide();
      return;
    }

    sizing();
    $.event.trigger({type: 'footer.show'});
    _appui.mainview.css({
      height: 150
    });
    _appui.footer.addClass('show');
    setVisibility(true);
  }

  function sizing() {
    $.event.trigger({type: 'footer.sizing'});
    $('.site-footer, .site-footer-content-wrapper').css({
      height: $(window).height()-210
    });
    setReady();
  }

  function setReady(){
    this.isReady = true;
    _appui.footer.addClass('loaded');
  }

  function hide() {
    $.event.trigger({type: 'footer.hide'});
    _appui.mainview.css({
      height: "auto"
    });
    _appui.footer.removeClass('show');
    setVisibility(false);
  }

  $footer.on({
    click: show,
    // mouseenter: active,
    mouseleave: hide
  });

  return {
    start: function() {
      sizing();
      setReady();
    }
  };
});