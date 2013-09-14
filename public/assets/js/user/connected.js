requirejs.config({
  baseUrl: "/assets/js/lib",
  paths: {
    "app": "/assets/js/app",
    "jquery": [
      "https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min",
      // load if CDN fails
      "jquery.min"
    ],
    "require": "/assets/js/lib/require"
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
    Modal.open({
      className: "user-connected",
      callback: openConnectedModal
    });

    function openConnectedModal(context)
    {
      $.get('/api/ui', { "name": "ajax.user.connected"}, function(html){
        context.empty().append(html);
      });
      try {
        $.get('/api/session', {
          "method": "forget",
          "keys": [
            "user_connected"
          ]
        }, function(){ return; });
      } catch(err) {
        console.log(err.message);
      }
    }
  });
});