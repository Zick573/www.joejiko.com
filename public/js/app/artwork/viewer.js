requirejs.config({
  baseUrl: "/js/lib",
  paths: {
    "app": "/js/app",
    "jquery": [
      "https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min",
      // load if CDN fails
      "jquery.min"
    ],
    "require": "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/js/libs/require"
  },
  waitSeconds: 30
});

requirejs.onError = function (err) {
  console.log("err: "+JSON.stringify(err));
  if(err.requireType === 'timeout') {
    console.log('modules: ' + err.requireModules);
  }

  throw err;
};

require(["require/domReady!"], function() {
  require(["jquery", "app/ui/_global/modal"], function($, Modal){
    // @todo forward/rewind controls
    // @todo go to first if last, last if first
    // @todo only 1 viewer open at a time
    function openPhotoModal(context, data)
    {
      var $img = $('<div class="photo-content" />'),
          $forward = $('<div class="photo-view-control photo-view-forward">&rarr;</div>'),
          $rewind = $('<div class="photo-view-control photo-view-rewind">&larr;</div>');
      $rewind.on('click', viewerRewind);
      $forward.on('click', viewerForward);
      // $img.on('load', function(){
      //   $(this).fadeIn('slow');
      //   context.trigger('loaded');
      // })
      // $img.prop('src', data.img_src);
      // $img.css({"width": "100%", "height": "auto"});
      $img.css({"background-image": "url("+data.img_src+")"});
      var $parent = context.parents('.modal');
      $parent.find('.modal-close-message').empty().append('(press ESC or click here to close)');
      context.empty().append($img, $rewind, $forward);
    }

    function viewerRewind(evt) {
      return;
    }

    function viewerForward(evt) {
      return;
    }

    $('.artwork img').on('click', function(){
      Modal.open({
        className: "photo-view",
        callback: openPhotoModal,
        data: {
          img_src: $(this).attr('data-img-full')
        }
      });
    });

    return {}
  });
});