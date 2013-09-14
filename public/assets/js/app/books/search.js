define(['jquery'], function($, Tooltip){
  var query;
  function search(params) {
    if(typeof(params.q) === "undefined"){
      if(!params.q || 0 === params.q.length){
        alert('no query');
        return false;
      }
    }

    // update url
    window.history.pushState("", "Search: "+params.q, "?q="+params.q);

    $.get('/api/freebase',
      {
        query: params.q,
        filter: '(any type:/book/book)',
        output: '(created_by)'
      },
      function(resp){
        $result = $(document).find('[data-part=result]');
        $resultContent = $('<ul />');
        $.each(resp, function(i, v) {
          // generate result DOM
          $resultContent.append('<li>'+v.name+' ('+v.type+') by '+v.created_by+'</li>');
        });
        $result.empty().append($resultContent);
      }
    );
  }

  function bind($this) {
    that = $this;
    $this.find('[data-part=search]').on('click', function(){
      // do search
      search({q: that.find('[data-part=query]').prop('value')});
    });
  }

  function loadState()
  {
    var urlParams;
    var match,
        pl     = /\+/g,  // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query  = window.location.search.substring(1);

    urlParams = {};
    while (match = search.exec(query))
       urlParams[decode(match[1])] = decode(match[2]);
    // console.log(JSON.stringify(urlParams));
    if(urlParams.q)
    {
      // perform query
     this.query = urlParams["q"];
     return true;
    }

    return false;
  }

  function start() {
    console.log("@books.start");
    bind( $(document).find('.books') );
    if(loadState()){
      // console.log("this query: "+this.query);
      $(document).find('[data-part=query]').prop('value', this.query);
      search({q: this.query});
    }
  }

  if($(document).find('.books').length) {
    start.call();
  }

  return {
    books: {
      info: "loaded"
    }
  };
});