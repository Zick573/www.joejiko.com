requirejs.config({
  baseUrl: "/js/lib",
  paths: {
    "app": "/js/app",
    "jquery": [
      "https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min",
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
  require(["jquery"], function($){
    function confirm_deployment() {
      $('body').addClass('confirm--active');

      $('body').on('keypress', handle_deploy_keypress);
      $('#passwd').on('keydown', handle_deploy_signal);
      $('.dcm-close').on('click', confirm_deployment_close);
    }

    function confirm_deployment_close() {
      $('body').removeClass('confirm--active');
      $('body').off('keypress', handle_deploy_keypress);
      $("#passwd").off('keydown', handle_deploy_signal);
      $('.dcm-close').off('click', confirm_deployment_close);
    }

    function handle_deploy_signal(evt) {
      if(13 == evt.which) {
        deploy_with_confirm();
      }
    }

    function handle_deploy_keypress(evt) {
      if(0==evt.which){
        confirm_deployment_close();
      }
    }

    function deploy_with_confirm() {
      $.post('./start', { passwd: $('#passwd').prop('value') }, function(resp) {
        swap_bg_img({"id": resp.status, "set": true});
        confirm_deployment_close();
        $('.main').empty().append(resp.output);
      });
    }

    function swap_bg_img(config){
      var bg_class_name = 'bg-'+config.id;
      var bg_set_class = (config.set) ? $('body').addClass(bg_class_name) : $('body').removeClass(bg_class_name);
    }

    $("#forward").on({
      'click': confirm_deployment,
      'mouseenter': function(evt){
        swap_bg_img({"id": "deploy", "set": true})
      },
      'mouseleave': function(evt){
        swap_bg_img({"id": "deploy", "set": false})
      }
    });

    $("#rewind").on({
      'mouseenter': function(evt){
        swap_bg_img({"id": "revert", "set": true});
      },
      'mouseleave': function(evt){
        swap_bg_img({"id": "revert", "set": false})
      }
    })
    return {}
  });
});