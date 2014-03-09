requirejs.config({
  baseUrl: "/js/lib",
  paths: {
    "app": "/js/app",
    "dojo": [
      "https://ajax.googleapis.com/ajax/libs/dojo/1.9.1/dojo/dojo"
    ],
    "jq": "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/js/libs/jquery/plugins",
    "jquery": [
      "https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min",
      "http://code.jquery.com/jquery-2.0.3.min",
      // load if official CDNs fail
      "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/js/libs/jquery/2.0.2/jquery.min"
    ],
    "jquery-migrate": [
      "http://code.jquery.com/jquery-migrate-1.2.1",
      "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/js/libs/jquery/jquery-migrate-1.2.1"
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
  require(["app/main"], function(App){
    App.start();
  });
});