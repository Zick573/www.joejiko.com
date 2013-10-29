requirejs.config({
  baseUrl: "/js/lib",
  paths: {
    "app": "/js/app",
    "jq": "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/js/libs/jquery/plugins",
    "jquery": [
      "https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min",
      // load if CDN fails
      "jquery/jquery.min"
    ],
    "require": [
        "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/js/libs/require"
    ]
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
  require(["app/scripts.min"], function(App){
    App.start();
  });
});