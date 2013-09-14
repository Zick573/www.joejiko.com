/*
 * JJCOM
 */
(function( JJCOM, $, undefined ){
  // private properties
  var  _error;
  var _readyInterval = window.setInterval(_jjcomReady, 500);
  var home = {
  	zeah: 0
  }

  // private methods
  function _jjcomReady()
  {
    // Check for presence of required DOM elements or other JS dependencies
    if(jQuery !== undefined)
    {
    	// it's ready!
      window.$ = jQuery;
      if(typeof($.waypoint) !== undefined){
	      window.clearInterval(_readyInterval);
	      JJCOM.initialize();
      }
    }
  }

JJCOM.api = function(params, scope, callback, dataType) {

}

JJCOM.home = {

	load: {
		init: function(){
			$("#content").removeClass("loading");
			$("#artboard").fadeIn('slow', function(){
				console.log("artboard loaded");
			});
			JJCOM.home.load.gallery();
			JJCOM.home.load.video();
			JJCOM.home.load.zeah();
		},

		about: function(){
			// load about
			$("#about, #content footer").fadeIn('slow');
		},

		bindings:function(){

		},

		gallery: function(){
			$("#video").hide();
			$("#media").fadeIn('fast');
			$("#gallery article").each(function(i,e){
				$(e).fadeIn('fast');
			});
			$("#gallery img").on('load', function(){
				$(this)
					.parents('article')
					.fadeIn('slow');
			});
		},

		video:function(){
			$("#video").fadeIn('fast');
			$(document).on('click','#video .tabs a', function(){
				$('.tabs li').removeClass('active');
				$(this).parent().addClass('active');
				JJCOM.home.video.loadSrc($(this).attr('href'));
				return false;
			});
		},

		zeah:function(){

			$("#services img").bind('load',function(){
				// console.log($(this).attr('src'));
				$(this).fadeIn();

				if(home.zeah >= 1) {
					console.log("zeah saw you");
					if(home.zeah==2){
						home.zeah++;
						zeahbomb = setTimeout(function(){JJCOM.home.zeah.animate();},1000);
					} else {
						home.zeah++;
						zeahbomb = setTimeout(function(){JJCOM.home.zeah.animate();},3000);
					}
				}
			});

			$("#services img").waypoint(function(event, direction){
				home.zeah++;
				JJCOM.home.zeah.animate();
			},{triggerOnce:true,offset:'100%'});
		}
	},

	video: {
		loadSrc:function(url){
			$('#video iframe').attr('src','https://www.youtube.com/embed/'+url);
		}
	},

	zeah:{
		animate: function(){
			if(home.zeah==1){
				home.zeah++;
				var zeahbomb = setTimeout(function(){JJCOM.home.zeah.animate();},3000);
			} else if(home.zeah==2){
				$("#services img").hide().attr('src','/images/home/zeah-crouch.png');
			} else if(home.zeah==3){
				$("#services img").hide().attr('src','/images/home/zeah-lunge.png');
			}
		}
	}
}

  JJCOM.page = {
  	functions: function(){

  	},

  	detect: function(){
  		return 'current page from URL';
  	}
  }

  JJCOM.initialize = function() {
    $ = jQuery;
    $(window).load(function(){
		JJCOM.home.load.init();
    });
  }

}( window.JJCOM = window.JJCOM || {}, jQuery ));