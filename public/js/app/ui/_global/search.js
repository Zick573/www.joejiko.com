define(["jquery", "app/ui/_global/modal"], function($, Modal){
  var _appui = {
    mainView: $('.main-view'),
    content: $('#content'),
    mainArticle: $('.main-article'),
    search: $('#search'),
    searchTrigger: $('.site-search-icon')
  },
  $search = _appui.search,
  $trigger = _appui.searchTrigger,
  active = false,
  results = {
    cleanup: function() {
      toggle();
      var $searchResults = $(document).find('.search-results');
      if($searchResults.length){
        // console.log("@search.results cleanup");
        $searchResults.remove();
        _appui.mainArticle.css('opacity', '1');
      }
    }
  };


  function blurKeyBindings(evt) {

  }

  function focusKeyBindings(evt) {
    // escape pressed
    if(evt.keyCode === 27)
    {
      // cleanup
      return results.cleanup();
    }

    if($search.is(":focus")){
      if(evt.keyCode === 13)
      {
        // enter pressed
      }

      // $('.main-article').css('opacity', '.1');
      if(!$(document).find('.search-results').length){
        Modal.open({
          html: '<div class="search-results"><div class="loading"><span class="loading-message">Searching...</a></div> <p>haha.. <strong>not really</strong>.</p><p>Use Google :|</p></div>'
          // @todo replace with actual callback
          // callback: searchModalCallback
        });
        // _appui.mainView.animate({scrollTop: 0}, 200);
        // _appui.content.prepend('<div class="search-results">Searching...</div>');
      }
    }
  }

  function searchModalCallback(context)
  {
    // @todo replace with actual content
    // $.get('/api/search?query=', function() {...}
  }

  function blur() {
    // console.log("@search just lost focus");
    // $search.parent().removeClass("searching");
    $search.off('keyup', blurKeyBindings);
  }

  function focus() {
    // console.log("@search is in focus");
    // $search.parent().addClass("searching");
    $search.on('keyup', focusKeyBindings);
  }

  function show() {
    $search.parent().addClass('searching');
    $search.focus();
    this.active = true;
  }

  function hide() {
    $search.parent().removeClass('searching');
    this.active = false;
  }

  function isActive() {
    return this.active;
  }

  function toggle() {
    if(!isActive()) {
      show();
      return;
    }

    hide();
  }

  return {
    start: function() {
      // console.log("@search start");
      $trigger.on({
        click: toggle
      });
      $search.on({
        focus: focus,
        blur: blur
      });
    }
  };
});