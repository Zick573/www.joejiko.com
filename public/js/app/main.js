define(["require","jquery"], function(require, $) {
  console.log("@app loaded");
  var _app = {
    resizeTimer: null
  }
  var _appui = {
    app: $('.app'),
    app_loading: $('.app-loading-msg'),
    main: $('.main'),
    mainSidebar: $('.main-sidebar'),
    sidebar: $('.site-sidebar')
  };

  function alertMessage() {
    alert($(this).attr('data-message'));
  }

  function loadingMessage(msg) {
    _appui.app_loading.empty().append(msg);
  }

  function ready() {
    // alert('app is ready!');
    $.event.trigger({type: "app.ui.ready"});
    var $app = $('.app');
    $app.removeClass('app--loading');
  }

  function resizeUi(){
    var viewportHeight = window.innerHeight ? window.innerHeight : $(window).height();
    // console.log('@sizing: viewportHeight: '+viewportHeight);
    _appui.main.animate({
      height: viewportHeight-65
    }, 500, function(){
      _appui.mainSidebar.animate({
        height: viewportHeight-125
      }, 500);
      _appui.sidebar.animate({
        height: viewportHeight-65
      }, 500);
      _appui.sidebar.find('.sidebar-module-wrapper').css({"height": viewportHeight-65});
    });
  }

  function resize() {
    // console.log("delay resize a bit.. ;]");
    // delay a bit..
    if(_app.resizeTimer) window.clearTimeout(_app.resizeTimer);
    _app.resizeTimer = window.setTimeout(resizeUi, 700);
  }

  function handleResize(evt) {
    $.event.trigger({type: 'resizeViews'});
    evt.stopPropagation();
    evt.stopImmediatePropagation();
  }

  function handleTrigger() {
    switch($(this).attr('data-trigger')) {
      case "message":
        alertMessage.apply(this);
        break;
      default:
        alert("not sure what to do.");
    }
    if($(this).is('a')){ return false; }
  }
  return {
    start: function(){
      $.event.trigger({type: "app.start"});
      $(document).ready(function(){
        var $app_loading_msg = $(document).find('.app-loading-msg');
        console.log($app_loading_msg.length);
        // load ui
        loadingMessage('Building UI');
        resize();

       loadingMessage('Registering events');

        // alerts
        $(document).on('click', '[data-trigger]', handleTrigger);

        // resize app view
        $(window).on('resize', handleResize);
        $(document).on('resizeViews', resize);

        loadingMessage('Accessing core functions');
        // do this last
        require(
          [
            "app/ui/_global/menu",
            "app/ui/_global/search",
            "app/ui/_global/user"
          ],
          function(Menu, Search, User){
            // start global modules
            Menu.start();
            Search.start();
            User.start();

            /**
             * @todo
            requireCondition({
              'steam':['app/gaming/steam'],
              'steam-widget': ['app/gaming/steam'],
              'books': ['app/books/search']
            });
             *
            **/

            if( $(document).find('.steam').length ){
              require(['app/gaming/steam'], function( Steam ) {
                console.log(JSON.stringify(Steam));
              });
            }

            if( $(document).find('#artboard').length ){
              require(['app/home/artboard'], function( Artboard ) {
                console.log(JSON.stringify(Artboard));
              });
            }

            if( $(document).find('.steam-widget').length ) {
              require(['app/gaming/steam'], function ( Steam ) {
                console.log(JSON.stringify(Steam));
              });
            }

            if( $(document).find('.books').length ) {
              require(['app/books/search'], function( BookSearch ) {
                console.log(JSON.stringify(BookSearch));
              });
            }

            if( $(document).find('.questions').length || $(document).find('.btn-ask').length ) {
              require(['app/questions/main'], function( Question ) {
                console.log( JSON.stringify(Question) );
              });
            }

            if( $(document).find('button.message-send').length ) {
              require(['app/contact/message'], function( Contact ) {
                console.log("@contact message functions loaded");
                $('button.message-send').on('click', function(evt){
                  $('.contact-message-wrap').addClass('sending');
                  var data = [];
                  $('.contact-message').find('input, textarea').each(function(index, elem){
                    data.push({"name": $(elem).prop('name'), "value": $(elem).prop('value')});
                  });

                  try {
                  Contact.send.apply(this, [data]);
                  } catch ( e ) {
                    console.log(e);
                  }

                  evt.stopImmediatePropagation();
                  evt.preventDefault();
                });
              });
            }

            // load last
            loadingMessage('loading tooltips, footer');
            require(["app/ui/_global/tooltip", "app/ui/_global/sidebar", "app/ui/_global/footer"], function(Tooltip, Sidebar, Footer){
              Tooltip.start();
              Sidebar.start();
              Footer.start();
            });
            ready();
        });
      });
    }
  };
});