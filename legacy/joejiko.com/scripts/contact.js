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
			} else if (response.status === 'not_authorized') {
				// not_authorized
			} else {
				// not_logged_in
			}
		 });

		function fblogin() {
				FB.login(function(response) {
						if (response.authResponse) {
								// connected
								fbtestAPI();
						} else {
								// cancelled
						}
				});
		}

		function fbtestAPI() {
				FB.api('/me', function(response) {
						$("#contact-name").prop("value", response.name);
						$("#contact-email").prop("value", response.email);
						$("#contact-optin").prop("checked", true);
				});
		}

		$('.f-button-signin').on('click', function(){ console.log("login with facebook.."); fblogin(); return false; });
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

	// @todo: test for date field compatibility using modernizr
	/* load dojo theme */
	$('body').addClass('claro');

	/* tab swapping */
	$('.tabs .active').append('<span></span>');
	spanxval = ($('h2.active').width()-28)/2;
	$('h2.active span').css('left', spanxval);

	/* set default to active */
	$('.tab-header li.default, fieldset.default').addClass('active');

	function fn_switch_modes(index)
	{
		// update page url/title
		params = {
			title: $('.tabs h2:eq('+index+') a').prop('title'),
			url: $('.tabs h2:eq('+index+') a').prop('href')
		};

		updatePage(params);

		// @to do: fade in
		$('.tabs .active span')
			.remove();
		$('.tab-header li.active, fieldset.active:not(.user)')
			.hide('fast');
		$('.tab-header li, .tabs h2, fieldset')
			.removeClass('active');
		$('.tab-header li:eq('+index+'), .tabs h2:eq('+index+'), fieldset:eq('+index+')')
			.fadeIn('fast')
			.addClass('active')
			;
		$('.tabs .active')
			.append('<span></span>');

		spanxval = ($('h2.active').width()-28)/2;
		$('h2.active span')
			.css('left', spanxval);

		console.log("check for editable");
		var ckelem = $('fielset.active:not(.user)').find('ck_editable');
		ckelem.attr('contenteditable', true);
	}

	$('.tabs a').on('click', function(e){
		e.preventDefault();
	});
	$('.tabs h2').on('click', function(){
		activeIndex = $('.tabs .active').index();
		tabIndex = $(this).index();
		if(tabIndex !== activeIndex){
			fn_switch_modes(tabIndex);
		}
	});

	/* make me @anonymous */
	function fn_anonymous_state()
	{
		if($('#anonymous').is(':checked')){
			$('#social-signin, #user-identity').hide('fast');
		} else {
			$('#social-signin, #user-identity').show('fast');
		}
	}

	$("#anonymous").on('click', function(){
		fn_anonymous_state();
	});

	fn_anonymous_state();

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

	/* @CKEDITOR instances */
	var defaultData = {},
			activeField = {};

	function setDefaultData(name, data)
	{
		defaultData[name] = data;
	}

	function getDefaultData(name)
	{
		return defaultData[name];
	}

	function editableFocus()
	{
		if(!this.checkDirty())
		{
			setDefaultData(this.name, this.getData());
			this.setData( '' );
			this.resetDirty();
		}
		else
		{
			// do nothing
		}
	}

	function editableBlur()
	{
		if(this.checkDirty())
		{
				// @validate
//				console.log(this.getData());
		}
		else
		{
			// reset
//			console.log("reset default..");
//			console.log(getDefaultData(this.name));
			this.setData(getDefaultData(this.name));
			this.resetDirty();
		}
	}
	CKEDITOR.on('loaded', function(evt){
		console.log("editor core object fully loaded");
	});
	function editableLoaded()
	{
		console.log("editor loaded: "+this.name);
	}

	function editableReady()
	{
		$("#"+this.name).removeClass('loading');
	}

	CKEDITOR.on('instanceCreated', function(){
		console.log("instance created");
	});

	function editableCreated()
	{
		console.log("editable created");
	}

	function initializeEditors()
	{
		var general = CKEDITOR.inline( 'editable1', {
			on: {
				'loaded': editableLoaded,
				'instanceReady': editableReady,
				focus: editableFocus,
				blur: editableBlur
			}
		});

		var project = CKEDITOR.inline( 'editable2', {
			on: {
				'instanceReady': editableReady,
				focus: editableFocus,
				blur: editableBlur
			}
		});

		var question = CKEDITOR.inline( 'editable3', {
			on: {
				'instanceReady': editableReady,
				focus: editableFocus,
				blur: editableBlur
			}
		});
	}

	initializeEditors();

	/* handle @buttons */
	$('#contact button').on('click', function(){
		if($(this).attr('type') !== 'submit' && $(this).parent().prop('id') !== 'social-signin' )
		{
			$(this).toggleClass('selected');
			if($(this).html()==="Other")
			{
				if($(this).hasClass('selected'))
				{
					$('.scope input[type=text]').fadeIn('fast');
				}
				else
				{
					$('.scope input[type=text]').fadeOut('slow');
				}
			}
		}
		else if($(this).parent().prop('id') == 'social-signin')
		{
			// do nothing
		}
		/* @submit form */
		else if($(this).attr('type') === 'submit')
		{
			try
			{
				if($("#contact fieldset:nth-of-type(1)").hasClass('active'))
				{
					if(general.checkDirty())
					{
						message = general.getData();
					}
					else
					{
						return false;
					}
				}
				else if($("#contact fieldset:nth-of-type(2)").hasClass('active'))
				{
					if(project.checkDirty())
					{
						message = project.getData();
					}
					else
					{
						return false;
					}
				}
				else if($("#contact fieldset:nth-of-type(3)").hasClass('active'))
				{
					if(question.checkDirty())
					{
						message = question.getData();
					}
					else
					{
						return false;
					}
				}
				else
				{
					// no message
					return false;
				}
			}

			catch (err)
			{
				console.log("error retrieving inline data");
			}

			data = {
				project: {
					type: $("#project-type").prop("value"),
					deadline: $("#deadline").prop("value"),
					budget: $("#budget").prop("value"),
					referral: $("#referral").prop("value"),
					website: {
						url: $("#website").prop("value"),
						ecommerce: $("#ecommerce").prop("checked"),
						redesign: $("#redesign").prop("checked"),
						cms: $("#cms").prop("checked")
					},
					design: {
						logo: $("#logo").prop("checked"),
						print: $("#print").prop("checked")
					}
				},
				from: {
					anonymous: $("#anonymous").prop("checked"),
					name: $('#contact-name').prop("value"),
					email: $('#contact-email').prop("value")
				},
				message: message,
				subscribe: $("#contact-optin").prop("checked"),
				send: true
			}

			try
			{
				$.ajax({
					type: "POST",
					url: "/contact",
					data: data,
					dataType: "json"
				}).done(function( msg ) {
					console.log(msg.success);
					if(msg && msg.success === 1)
					{
						$('#contact')
							.addClass('thanks')
							.empty()
							.append('<p>Thanks!</p>');
					}
					else
					{
						$('#contact').prepend('<p>There was an error sending your message. <br> Please email me directly. <a href="mailto:me@joejiko.com">me@joejiko.com</a></p>');
					}
				});
			}
			catch (err)
			{
				// remove form
				// direct user to basic contact options
				$("#contact").empty()
					.append('<p>The contact form had an issue :(<br>Please email me directly at <a href="mailto:me@joejiko.com">me@joejiko.com</a></p>');
				console.log("ajax error: "+err);
			}
		}
		return false;
	});

	$('.project button').on('click', function(){
		projectType=[];
		$.each($('fieldset.project button.selected'), function(i,e){
			projectType.push($(e).html());
		});

		//console.log(projectType);
		//console.log(projectType.indexOf("Artist website"));

		isWebsite = (function(){
			if(projectType.indexOf("Artist website") != -1 || projectType.indexOf("Business website") != -1 || projectType.indexOf("Personal website") != -1 || projectType.indexOf("Blog design") != -1)
			{
				return true;
			} else {
				return false;
			}
		})();

		isDesign = (function(){
			if(projectType.indexOf("Graphic design") != -1 || projectType.indexOf("Illustration") != -1)
			{
				return true;
			} else {
				return false;
			}
		})();

		if(isWebsite)
		{
			$("#website-design-info").fadeIn("slow");
		}
		else
		{
			$("#website-design-info").slideUp("fast");
		}

		if(isDesign)
		{
			$("#graphic-design-info").fadeIn("slow");
		}
		else
		{
			$("#graphic-design-info").slideUp("fast");
		}

	});

	/* @event checkbox selected */
	$('.highlight li').on('click', function()
	{
		/* @note: causing problems with redesign textbox
		$checkbox = $(this).find('input[type=checkbox]');
		if($checkbox.is(":checked"))
		{
			$checkbox.prop('checked', false);
		}
		else
		{
			$checkbox.prop('checked', true);
		}
		$checkbox.trigger('change');
		*/
	});

	$('.highlight [type=checkbox]').on('change', function()
	{
		if($(this).is(":checked"))
		{
			// highlight parent LI
			$(this).parents('li').addClass('selected');
		}
		else
		{
			$(this).parents('li').removeClass('selected');
		}
	});

	/* @redesign checkbox */
	$("#redesign").on('change', function(){
		$urltbox = $(".website-url");
		if($(this).is(':checked'))
		{
			$urltbox
				.fadeIn('slow')
				.css('display', 'inline-block');
		}
		else
		{
			$urltbox.fadeOut('fast');
		}
	});

	// ask if they want to send anonymously when the email/name is blank
	// @dojo
	require(["dojo/ready", "dojo/parser", "dijit/Calendar", "dijit/form/DateTextBox", "dijit/form/HorizontalSlider"], function(ready, parser, HorizontalSlider){
		ready(function(){
			parser.parse();

		});
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
			else
			{
				// console.log(e.data);
				// get data from teh Googs
				$.getJSON('https://www.googleapis.com/oauth2/v1/userinfo', { access_token : resp.access_token }, function(data)
				{
					// fill das form
					$('#contact-name').prop("value", data.name);
					$('#contact-email').prop("value", data.email);
					$('#service').prop('value', 'google');

					$id = $('<input type="hidden">');
					$id.prop({
						'name' : "id",
						'value' : data.id
					});
					$("#user-identity").append($service,$id);
				});
			}
		}
	},false);

});