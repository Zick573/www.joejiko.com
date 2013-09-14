requirejs.config({
  baseUrl: "/assets/js/lib",
  paths: {
    "app": "/assets/js/app",
    "jquery": [
      "https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min",
      // load if CDN fails
      "jquery/jquery.min"
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
  require(["app/scripts.min"], function(App){
    App.start();
  });
});