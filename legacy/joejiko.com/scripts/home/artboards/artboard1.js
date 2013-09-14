	<!-- signature stars exploding -->
	$('#jikosig').mouseover(function(){
		$("#starburst").sprite({fps: 10, no_of_frames: 5});
		stopburst = setTimeout(function(){ $("#starburst").spStop(true); }, 5000);
		_gaq.push(['_trackEvent', 'home', 'animate', 'signature']);
	}).mouseout(function(){
		$("#starburst").spStop(true).destroy();
	});
	var stopflames = "";
	$('.charizard').on({
		mouseover: function(){
			$('.charizard img').stop(true,true).fadeOut('fast');
			$('.charizard').sprite({
				fps: 6, 
				no_of_frames: 8, 
				start_at_frame: 2
			});
			_gaq.push(['_trackEvent', 'home', 'animate', 'charizard']);
		},
		mouseout: function(){
			$('.charizard').spStop(true).destroy();
			$('.charizard img').stop(true,true).fadeIn('slow');
		}
	});
	
	<!-- eyes left -->
	$("#jikosig, #starburst").mouseover(function(){
		$("#jikoeyes").addClass("eyesleft2");
	}).mouseout(function(){
		$("#jikoeyes").removeClass("eyesleft2");
	});
	
	<!-- eyes left 2 -->
	$("#introduction h2").mouseover(function(){
		$("#jikoeyes").addClass("eyesleft");
	}).mouseout(function(){
		$("#jikoeyes").removeClass("eyesleft");
	});
	
	<!-- eyes down -->
	$("#socialbtnwrap").mouseover(function(){
		$("#jikoeyes").addClass("eyesdown");
	}).mouseout(function(){
		$("#jikoeyes").removeClass("eyesdown");
	});
	
	<!-- eyes right -->
	$("#status").mouseover(function(){
		$("#jikoeyes").addClass("eyesright");
	}).mouseout(function(){
		$("#jikoeyes").removeClass("eyesright");
	});
	
	<!-- eyes crossed -->
	$("#jikoeyes").mouseover(function(){
		$(this).addClass("crossed");
	}).mouseout(function(){
		$(this).removeClass("crossed");
	});
	
	<!-- eyes up -->
	$("#jj_header, #socialbar, #socialshare, #error").mouseover(function(){
		$("#jikoeyes").addClass("eyesup");
	}).mouseout(function(){
		$("#jikoeyes").removeClass("eyesup");
	});
	
	/* image loading/fade in TEST */
	function swapImage($img,src){
		$img.bind('load',function(){
			$(this).fadeIn();
		});

		$img.attr('src',src);
	}
	
	<!-- eyes -->
	var eyes = 0;
	function rotateEyes(){
		if(eyes < 3) { eyes++; } else { eyes = 0; }
		switch(eyes){
			case 3:
				eyessrc = "joejiko-eyes-demon.png";
				break;
			case 2:
				eyessrc = "joejiko-eyes-glow.png";
				break;
			case 1:
				eyessrc = "joejiko-eyes-galaxy.png";
				break;
			default:
				eyessrc = "joejiko-eyes.png";
		}
		return "/images/home/"+eyessrc;
	}
	
	<!-- mouth -->
	var mouth = 0;
	function chomp()
	{
		if(mouth == 0){
			$("#jikomouth img").toggleClass("chomp");
		} else if($("#jikomouth img").hasClass('chomp')){
			$("#jikomouth img").removeClass('chomp');
		}
	}	
	function rotateMouth(){
		if(mouth < 1) { mouth++; } else { mouth = 0; }
		switch(mouth){
			case 1:
				mouthsrc = "joejiko-mouth3.png";
				break;
			default:
				mouthsrc = "joejiko-mouth2.png";
		}
		
		return "/images/home/"+mouthsrc;
	}
	
	var chomptimeout;
	$("#jikomouth img").mouseleave(function(){
		// clear timer
		chomptimeout = clearInterval(chomptimeout);
		if($(this).hasClass('chomp')){
			$(this).removeClass('chomp');
		}
	});
	
	$("#jikoeyes, .clickme3").click(function(){
		_gaq.push(['_trackEvent', 'home', 'click', 'swap eyes']);
		$("#jikoeyes").css('background-image','url('+rotateEyes()+')');
	});
	
	
	$("#jikomouth").click(function(){
		_gaq.push(['_trackEvent', 'home', 'click', 'swap mouth']);
		if(mouth == 0){
			chomptimeout = setInterval(function(){chomp()},100);
		}
		swapImage($("#jikomouth img"),rotateMouth());
	});
	
	<!-- clothing -->
	var clothing = 0;
	function rotateClothes(){
		// set array of clothing sources
		sources = [
			"vampire-galaxy",
			"vampire-buttonup",
			"crazy",
			"jikobot",
			"ghost"
		];
		if(clothing < (sources.length -1)){ clothing++; } else { clothing = 0; }
		clothingsrc = sources[clothing]+".png";	
		
		return "/images/home/portraits/"+clothingsrc;
//		$img.replaceWith('<img src="/images/public/about/'+clothingsrc+'">');
	}
	$("#jikoportrait img, .clickme").click(function(){
		_gaq.push(['_trackEvent', 'home', 'click', 'swap clothing']);
		swapImage($("#jikoportrait img"),rotateClothes());
		$("#joejiko-art").attr('class', function() { 
			if(clothing > 0) { 
					return 'no-extras'; 
				} else { 
					return 'extras'; 
			} 
		});
	});
	
	$("#hints,.hints").on({
		mouseenter: function(){
			$('.click-map').stop(true,true).fadeIn(function(){
					_gaq.push(['_trackEvent', 'home', 'mouseenter', 'hints']);
			});
		},
		mouseleave: function(){
			$('.click-map').stop(true,true).fadeOut();
		}
	});
	
	
	function fn_animateCloud(){
		// to do.. separate cloud segments
		// fadein small pieces and +=/-= 1
		// +=/-= 5 on the large bubble
		$("#introduction h2").animate({
			top:'+=2'
		}, 500, function(){
			$("#introduction h2").animate({
				top:'-=2'
			}, 500, function(){
				fn_animateCloud();
			});
		});
	};
//	fn_animateCloud();