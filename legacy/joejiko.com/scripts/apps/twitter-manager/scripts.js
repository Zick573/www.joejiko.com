/*
 * JJCOM namespace
 */
(function( JJCOM, $, undefined ){
  // private properties
  var  _error;
  var _readyInterval = window.setInterval(_jjcomReady, 500);

  // public properties
  JJCOM.public = 42;
  JJCOM.user;

  // private methods
  function _jjcomReady()
  {
    // Check for presence of required DOM elements or other JS dependencies
    // @todo create function _readyInterval
    if(jQuery !== undefined)
    {
    	// it's ready!
      window.$ = jQuery;
      window.clearInterval(_readyInterval);
      JJCOM.initialize();
    }
  }

  function _login()
  {
  	// detect login type
  	var service = $(this).attr('data-login');
  	service = service.charAt(0).toUpperCase() + service.slice(1);
  	console.log('login: '+service);
  	if(typeof(JJCOM["_loginWith"+service]()) === 'function')
  	{
  		console.log('before _loginWith'+service);
  		JJCOM["_loginWith"+service]();
  	}
  	//_api();
  	return false;
  }

  JJCOM._loginWithTwitter = function() {
  	JJCOM.login.oauth('http://joejiko.com/login/twitter');
		return false;
  }

	JJCOM.login = {
		oauth: function(url){
			var specs = 'toolbar=0,location=0,directories=0,status=yes,menubar=0,scrollbars=yes,resizable=yes,width=960,height=600,titlebar=yes';
			window.open(url, 'oauth', specs);
		},

		site: function(user)
		{
			// login through joejiko.com
			if(typeof(user) === 'object')
			{
				$.post('/login', user,

					function(response){
						JJCOM.user = response;
						if(response.email)
						{
							window.location = '/';
							return;
						}
				});
			}
		},

		transition: function(){
			$('.user-login').css({'opacitiy': '1'}).empty().append('logged in');
		},

		mask: function(){
			$('.user-login').css({'opacity': '.1'});
		}
	}

  // public methods
  JJCOM.initialize = function() {
    $ = jQuery;

    // prompt login
    $(document).on('click', '[data-login]', function(){
    	JJCOM.login.mask();
    	_login.apply(this);
    });
  }

}( window.JJCOM = window.JJCOM || {}, jQuery ));

/* @oauth message from parent */
/* Create IE + others compatible event handler */
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent",
		eventer = window[eventMethod],
		messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message"
		;
/* Listen to message from child window */
eventer(messageEvent,function(e) {
	// trusted?
	if(e.origin == "http://joejiko.com")
	{
		resp = JSON.parse(e.data);
		if(resp.service == "twitter")
		{
			console.log("from teh Twits: "+JSON.stringify(resp));
			var user = {
				service: 'twitter',
				id: resp.id,
				name: resp.name,
				location: resp.location,
				token: resp.user_token,
				secret: resp.user_secret
			};
			// save user
			JJCOM.login.site(user);

			// ask for email
			$.get('/api/forms', {	name: 'login/twitter-ext'	},
			function(data){

				// popup form asking for email
				$jPop = $('<div class="jPop"></div>');
				$jPop.append(data);
				$('body').append($jPop);

				// wait for email
				$(document).on('submit', '.user-extended-info', function(){
					JJCOM.user.email = $('.user-extended-info input[type=email]').val();
					var user = JJCOM.user;
					console.log("user status: "+user.status);
					if(user.status === 'logged in')
					{
						// all good
						window.location('/');
						return;
					}

					$jPop.empty().append("Couldn't log you in. Contact me or try again later.");
					return false;
				});
			}, 'html');
		}
	} // origin check
},false);