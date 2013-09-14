$(document).ready(function(){

	/* @todo: display a checkbox in the corner

	 * @todo: validate on keypress/change

	 * @todo: if valid, make the send button green

	 * @todo: .addClass('valid') if valid or

	 *        .removeClass if invalid

	 */



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

				siteLogin();

			} else if (response.status === 'not_authorized') {

				// not_authorized

				console.log("ERR: not authorized");

			} else {

				// not_logged_in

				console.log("user not logged in");

			}

		});



		function fblogin() {

			console.log('do login');

			FB.login(function(response) {

					if (response.authResponse) {

							// connected

							siteLogin();

					} else {

							// cancelled

					}

			}, {scope: 'email'});

		}



		function siteLogin() {

			$('.login-wrap').remove();

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

						console.log(JSON.stringify(response));

				});

			});

		}



		$('.f-button-signin').on('click', function(){

			console.log("login with facebook..");

			fblogin();

			return false;

		});

	};



  /* Load the SDK Asynchronously */

  (function(d){

     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];

     if (d.getElementById(id)) {return;}

     js = d.createElement('script'); js.id = id; js.async = true;

     js.src = "//connect.facebook.net/en_US/all.js";

     ref.parentNode.insertBefore(js, ref);

   }(document));



	/* sign in with twitter */

	$(".t-button-signin").on('click', function () {

		window.open(

			"http://joejiko.com/login/twitter", 'oauth', 'toolbar=0,location=0,directories=0,status=yes,menubar=0,scrollbars=yes,resizable=yes,width=960,height=600,titlebar=yes'

		);

	});



	/* sign in with google */

	$('.g-button-signin').on('click', function(){

		window.open(

			"https://accounts.google.com/o/oauth2/auth?"

				+ "scope=https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email"

				+ "&state=contact"

				+ "&redirect_uri=http://joejiko.com/login/google"

				+ "&response_type=token"

				+ "&client_id=29103454985.apps.googleusercontent.com", 'oauth', 'toolbar=0,location=0,directories=0,status=yes,menubar=0,scrollbars=yes,resizable=yes,width=960,height=600,titlebar=yes'

		);

	});



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

				$('#contact-name').prop("value", resp.name);

				$('#service').prop('value', 'twitter');



				/* @notice: twitter doesn't provide email

				 * @todo: store/retrieve user data from local database

				 * @todo: cache last modified

				 */

				$tooltip = $("<div class='tooltip'><p>Please enter your email. Twitter doesn't provide it. I'll remember it for you next time.</p></div>");

				$("#contact-email").after($tooltip);

				$('.tooltip').on('click', function(){ $(this).remove(); });

				$("#contact-email").on('keyup', function() {

					$('.tooltip').remove();

					if($("#contact-optin").prop("checked") == false)

					{

						$("#contact-optin").prop("checked", true);

					}

				});

			}

			// not twitter (aka google)

			else

			{

				// console.log(e.data);

				$.getJSON('https://www.googleapis.com/oauth2/v1/userinfo', { access_token : resp.access_token }, function(data)

				{



					// data from teh Googs

					$('.login-wrap').remove();



					FB.api('/me', function(response){

						// login through joejiko.com

						$.post('/login',

						{

							'service': 'google',

							'id': data.id,

							'email': data.email,

							'name': data.name

						}, function(response){

							console.log(JSON.stringify(response));

						});

					});



				});

			}



		} // origin check

	},false);



});