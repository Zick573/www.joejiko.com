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

  JJCOM._loginWithFacebook = function(){
		/* facebook signin */
		$('body').append('<div id="fb-root"></div>');

		window.fbAsyncInit = function() {
			FB.init({
				appId      : '160372647359458', // App ID
				channelUrl : '//joejiko.com/scripts/libraries/facebook/channel.html', // Channel File
				status     : true, // check login status
				cookie     : true, // enable cookies to allow the server to access the session
				xfbml      : true  // parse XFBML
			});

			// Additional init code here
			FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
					// connected
					FB.api('/me', function(response){

						// login through joejiko.com
						$.post('/login',
							{
								'service': 'facebook',
								'id': response.id,
								'email': response.email,
								'name': response.name
							},

							function(response){
								if(response.result && response.result === true && response.status == "logged in")
								{
									window.location = "/";
									return;
								}

								console.log("something went wrong :/");
						});
					});
				}
				else if (response.status === 'not_authorized')
				{
					// not_authorized
					console.log("ERR: not authorized");
				}
				else
				{
					// not_logged_in
					console.log("user not logged in");
					FB.login(function(response) {
							if (response.authResponse) {

									// connected
									FB.api('/me', function(response){

										// login through joejiko.com
										$.post('/login',
											{
												'service': 'facebook',
												'id': response.id,
												'email': response.email,
												'name': response.name
											},

											function(response){
												if(response.result && response.result === true && response.status == "logged in")
												{
													window.location = "/";
													return;
												}

												console.log("Something went wrong :/ "+JSON.stringify(response));
										});
									});
							}
							else
							{
								// cancelled
								console.log("user cancelled login");
							}

					}, {scope: 'email'});
				}
			});
		};

	  /* Load the SDK Asynchronously
			@todo: detect if already loaded
	  */
	  (function(d){
	     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement('script'); js.id = id; js.async = true;
	     js.src = "//connect.facebook.net/en_US/all.js";
	     ref.parentNode.insertBefore(js, ref);
	   }(document));
  }

  JJCOM._loginWithGoogle = function() {
  	var url = "https://accounts.google.com/o/oauth2/auth?"
				+ "scope=https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.email"
				+ "&state=user.login"
				+ "&redirect_uri=http://joejiko.com/login/google"
				+ "&response_type=token"
				+ "&client_id=29103454985.apps.googleusercontent.com";
		JJCOM.login.oauth(url);
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
							console.log("email found");
							window.location.href = '/';
							return;
						}
						JJCOM.login.transition();
				});
			}
		},

		transition: function(){
			$('.user-login').css({'opacity': '1'}).empty().append('logged in');
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
				location: resp.location
			};
			// save user
			JJCOM.login.site(user);

			// ask for email
			$.get('/api/forms', {	name: 'login/twitter-ext'	},
			function(data){

				// popup form asking for email
				$jPop = $('<div class="jPop"></div>');
				$jPop.append(data);
				$('.user-login').css({'opacity': '1'}).empty().append($jPop);

				// wait for email
				$(document).on('submit', '.user-extended-info', function(){
					JJCOM.user.email = $('.user-extended-info input[type=email]').val();
					var user = JSON.parse(JJCOM.login.site(JJCOM.user));
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
		// not twitter (aka google)
		else
		{
			var user;
			// console.log(e.data);
			// id, email, verified_email
			$.getJSON('https://www.googleapis.com/oauth2/v2/userinfo', { access_token: resp.access_token }, function(data)
			{
				console.log('from teh Googs: '+JSON.stringify(data));
				var user = {
					service: 'google',
					id: data.id,
					name: null,
					email: data.email,
					verified_email: data.verified_email
				};
				// profile info
				$.getJSON('https://www.googleapis.com/plus/v1/people/me', { access_token: resp.access_token }, function(data)
				{
					// data from teh Googs
					console.log('more from teh Googs: '+JSON.stringify(data));
					user.name = data.displayName;

					JJCOM.login.site(user);
				});
			});
		}
	} // origin check
},false);