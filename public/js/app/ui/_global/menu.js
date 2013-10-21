define(["jquery"], function($) {
  var isVisible = false,
      _appui = {
        app: $('.app'),
        header: $('.site-header'), //
        menu: $('.site-nav-wrap'), //
        logo: $('.site-logo'), //
        main: $('.main') //
      };
  function getVisibility(){
    return this.isVisible;
  }
  function setVisibility(showOrHide) {
    this.isVisible = showOrHide;
  }
  function show() {
    _appui.app.addClass('app-menu--active');
    setVisibility(true);
  }

  function handleClick(evt) {
    if(!getVisibility()) {
      show();
      if($(this).is('a')) {
        return evt.preventDefault();
      }
    } else { hide(); }
  }

  function hide() {
    _appui.app.removeClass('app-menu--active');
    setVisibility(false);
  }

  function active() {
    if(!getVisibility()) {
      // console.log("@menu timer started");
      this.timer = window.setTimeout(show, 700);
    }
  }

  function stop() {
    if(this.timer !== null) {
      // console.log("@menu timer cleared");
      window.clearTimeout(this.timer);
    }
  }

  return {
    start: function() {
      // console.log("@menu start");
      $('.site-logo #Logo, .diamond, .menu-trigger').on({
        click: handleClick,
        mouseenter: active, // start timer
        mouseleave: stop
      });
      _appui.header.on('mouseleave', hide);
      $('.site-sidebar, #search').on('mouseenter', hide);
      $('.site-nav-close').on('click', function(evt){
        hide();
        evt.preventDefault();
      });
    }
  };
});