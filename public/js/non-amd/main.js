(function(JJCOM, $, undefined){
  var _readyInterval = window.setInterval(_jjcomReady, 500),
  _app = {};
  function _jjcomReady()
  {
    if(jQuery !== undefined)
    {
      window.$ = jQuery;
      window.clearInterval(_readyInterval);
      $(document).ready(function(){
        console.log('document ready');
        JJCOM.initialize();
      });
    }
  }

  JJCOM.app = {

    bindings: function() {
      $(document).on('click', '[data-trigger]', function(){
        switch($(this).attr('data-trigger')){
          case "message":
            alert($(this).attr('data-message'));
            break;
        }
      });

      $(window).on('resize', function(e){
        $.event.trigger({type: 'resizeViews'});
      });

      $(document).on('keyup', function(e){
        if(e.keyCode === 27)
        {
          // escape pressed
          $(document).find('.search-results').remove();
          _app.mainArticle.css('opacity', '1');
          return false;
        }

        if($("#search").is(":focus")){
          if(e.keyCode === 13)
          {
            // enter pressed
            alert('force search!');
          }

          $('.main-article').css('opacity', '.1');

          if(!$(document).find('.search-results').length){
            _app.mainView.animate({scrollTop: 0}, 200);
            _app.content.prepend('<div class="search-results">Searching...</div>');
          }
        }
      });
    },

    events: function() {
      $(document).on('resizeViews', JJCOM.app.sizing);
    },

    setup: function(){
      _app.header = $('.site-header');
      _app.nav = $('.site-nav-wrap');
      _app.logo = $('.site-logo');
      _app.mainView = $('.main-view');
      _app.sidebar = $('.site-sidebar');
      _app.main = $('.main');
      _app.content = $('#content');
      _app.mainArticle = $('.main-article');
      _app.footer =   $('.site-footer');
      this.sizing();
      this.events();
      this.bindings();
    },

    search: {
      init: function(){
        this.bindings();
      },
      bindings: function(){
        $("#search").on({
          'focus': function(){
            console.log("search is in focus");
            $("#search").parent().addClass('searching');
          },
          'blur': function(){
            console.log("search just lost focus");
            $("#search").parent().removeClass('searching');
          }
        });
      }
    },

    sizing: function(){
      var viewportHeight = window.innerHeight ? window.innerHeight : $(window).height();
      console.log('@sizing: viewportHeight: '+viewportHeight);
      _app.main.animate({
        height: viewportHeight-65
      }, 500, function(){
        _app.sidebar.animate({
          height: viewportHeight-65
        }, 500);
      });
    },

    init: function() {
      this.setup();
      JJCOM.menu.init();
      this.search.init();
      JJCOM.footer.init();
    }
  };

  JJCOM.menu = {

    isVisible: false,
    wait: null,

    init: function(){
      this.bindings();
    },

    bindings: function(){
      $('.site-logo #Logo, .diamond, .menu-trigger').on({
        'click': function(){
          JJCOM.menu.clearWait();
          JJCOM.menu.show();
        },
        'mouseenter': function(){
          JJCOM.menu.startWait();
        },
        'mouseleave': function(){
          JJCOM.menu.clearWait();
        }
      });

      _app.header.on('mouseleave', function(){
          JJCOM.menu.clearWait();
          JJCOM.menu.show(false);
      });

      $('.site-sidebar, #search').on('mouseenter', function(){
        JJCOM.menu.show(false);
      });

      $('.site-nav-close').on('click', function(e){
        JJCOM.menu.show(false);
        return false;
      });
    },

    clearWait: function(){
        // console.log('@begin clear wait')
        if(this.wait !== null){
          window.clearTimeout(JJCOM.menu.wait);
        }
    },

    startWait: function(){
      // console.log('@begin start wait');
      this.clearWait();
      JJCOM.menu.wait = window.setTimeout( JJCOM.menu.show, 500);
    },

    show: function(toggle){
      if(toggle===false){
        _app.nav.removeClass('show');
        _app.logo.removeClass('active');
        _app.main.removeClass('dim');
        this.isVisible = false;
        return;
      }
      _app.nav.addClass('show');
      _app.logo.addClass('active');
      _app.main.addClass('dim');
      this.isVisible = true;
    },

    toggle: function(){
      if(this.isVisible){
        this.show(false);
        return;
      }
      this.show(false);
    }
  };

  JJCOM.footer = {
    bindings: function(){
      $('.site-footer-heading').on({
        'mouseenter': function(e){
          $('.site-footer, .site-footer-content-wrapper').css('height', $(window).height()-300);
          _app.footer.addClass('show');
        }
      });
      $('.site-footer').on({
        'mouseleave': function(e){
          _app.footer.removeClass('show');
        }
      });
    },
    init: function(){
      this.bindings();
    }
  };

  JJCOM.initialize = function() {
    $ = jQuery;
    JJCOM.app.init();
  }
}( window.JJCOM = window.JJCOM || {}, jQuery ));
