define(['jquery', 'jq/spritely/jquery.spritely-0.6.1', 'jq/waypoints/jquery.waypoints.min'], function($){
    $(document).on('click',"#recent-question h2",function(){
      //          _trackEvent(category, action, opt_label, opt_value, opt_noninteraction)
      _gaq.push(['_trackEvent', 'home', 'click', 'highlight-ask me anything']);
      $.colorbox({href:"/lupus/?questions",data:{ask:"ask"}});
      return false;
    });

    $(".more").on('click',function(){
      $.colorbox({width: "960px", height: "500px", top: "10%;",inline:true,href:$(".more-details")});
      _gaq.push(['_trackEvent', 'navigation', 'click', 'more']);
      return false;
    })
    $(".question").on('click',function(evt){
      _gaq.push(['_trackEvent', 'navigation', 'click', 'ask a question']);
      $.colorbox({href:"/lupus/?questions",data:{ask:"ask"}});

      return false;
    });

      var id = '#dialog';

      //Get the screen height and width
      var maskHeight = $(document).height();
      var maskWidth = $(window).width();

      //Set heigth and width to mask to fill up the whole screen
      $('#mask').css({'width':maskWidth,'height':maskHeight});

      //transition effect
      setTimeout(function(){
        $('#mask').fadeIn(1000);
        $('#mask').fadeTo("slow",0.8);

        //Get the window height and width
        var winH = $(window).height();
        var winW = $(window).width();

        //Set the popup window to center
        $(id).css('top',  winH/2-$(id).height()/2);
        $(id).css('left', winW/2-$(id).width()/2);

        //transition effect
        $(id).fadeIn(2000);
      },15000);

    //if close button is clicked
    $('.window .close').click(function (e) {
      //Cancel the link behavior
      e.preventDefault();

      $('#mask').hide();
      $('.window').hide();
    });

    //if mask is clicked
    $('#mask').click(function () {
      $(this).hide();
      $('.window').hide();
    });
  $(window).load(function(){
    $("#artboard").fadeIn();
    fn_loadGallery();
    $("#services img").waypoint(function(event, direction){
      console.log("zeah sees you");
      zeah++;
      animateZeah();
    },{triggerOnce:true,offset:'100%'});

    var headlines = setTimeout(function(){
      $.getJSON('/lupus/?questions&view=1',function(response){
        questionhtml = $("<p></p>");
        questionhtml.attr('id','recent-question');
        questionhtml.append("<h2>Ask me anything</h2>");
        questionhtml.append($(response.feed));
        questionhtml.hide();
        $("#status").append(questionhtml);
        $("#recent-question a").on('click',function(){
          return false;
        });
        var switchstatus = setTimeout(function(){
          $("#status p").fadeOut();
          qtimeago = $.timeago($("#recent-question .date time").html());
          $("#recent-question .date time").empty().append("asked "+qtimeago);
          $("#recent-question").fadeIn('slow');
        }, 3000);
      })
    }, 3000)
  });
  $(document).on('click','#video .tabs a', function(){
    fn_loadVideoSrc($(this).attr('href'));
    return false;
  });
  function fn_loadVideo(){
    ytvideo = $('<article id="video"><iframe width="420" height="315" frameborder="0" allowfullscreen="" src=""></iframe></article>');
    $('#media').append(ytvideo);
    // intro video: 9dm0AjlRvkw
    // vlog1: ?
    // vlog2: wtI6TL516pU
    fn_loadVideoSrc('wtI6TL516pU');
    // add tabs
    $(ytvideo).append('<ul class="tabs"><li><a href="9dm0AjlRvkw">Intro</a></li><li><a href="i_oo5RfbF-c">vlog #1</a></li><li class="active"><a href="wtI6TL516pU">vlog#2</a></li></ul>');
    $('#media iframe').load(function(){
      $("#media").fadeIn();
    });
  }

  function fn_loadVideoSrc(vid){
    $('#video iframe').attr('src','http://www.youtube.com/embed/'+vid);
  }
  //<!-- signature stars exploding -->
  $('#jikosig').mouseover(function(){
    $("#starburst").sprite({fps: 10, no_of_frames: 5});
    stopburst = setTimeout(function(){ $("#starburst").spStop(true); }, 5000);
    _gaq.push(['_trackEvent', 'home', 'animate', 'signature']);
  }).mouseout(function(){
    $("#starburst").spStop(true).destroy();
  });
  var stopflames = "";
  $('.charizard').mouseover(function(){
    $('.charizard img').stop(true,true).fadeOut('fast');
    $('.charizard').sprite({
      fps: 8,
      no_of_frames: 8,
      start_at_frame: 2
    });
    _gaq.push(['_trackEvent', 'home', 'animate', 'charizard']);
  }).mouseout(function(){
    $('.charizard').spStop(true).destroy();
    $('.charizard img').stop(true,true).fadeIn('slow');
  });

  //<!-- eyes left -->
  $("#jikosig, #starburst").mouseover(function(){
    $("#jikoeyes").addClass("eyesleft2");
  }).mouseout(function(){
    $("#jikoeyes").removeClass("eyesleft2");
  });

  //<!-- eyes left 2 -->
  $("#introduction h2").mouseover(function(){
    $("#jikoeyes").addClass("eyesleft");
  }).mouseout(function(){
    $("#jikoeyes").removeClass("eyesleft");
  });

  //<!-- eyes down -->
  $("#socialbtnwrap").mouseover(function(){
    $("#jikoeyes").addClass("eyesdown");
  }).mouseout(function(){
    $("#jikoeyes").removeClass("eyesdown");
  });

  //<!-- eyes right -->
  $("#status").mouseover(function(){
    $("#jikoeyes").addClass("eyesright");
  }).mouseout(function(){
    $("#jikoeyes").removeClass("eyesright");
  });

  ////<!-- eyes crossed -->
  $("#jikoeyes").mouseover(function(){
    $(this).addClass("crossed");
  }).mouseout(function(){
    $(this).removeClass("crossed");
  });

  ////<!-- eyes up -->
  $("#jj_header, #socialbar, #socialshare, #error").mouseover(function(){
    $("#jikoeyes").addClass("eyesup");
  }).mouseout(function(){
    $("#jikoeyes").removeClass("eyesup");
  });


  // image loading/fade in TEST
  function swapImage($img,src){
    $img.bind('load',function(){
      $(this).fadeIn();
    });
    $img.attr('src',src);
  }

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
    return "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/artboard/1/"+eyessrc;
  }
  var zeah = 0;
  $("#services img").bind('load',function(){
    console.log($(this).attr('src'));
    $(this).fadeIn();

    if(zeah >= 1) {
      console.log("set timeout");
      if(zeah==2){
        zeah++;
        zeahbomb = setTimeout(function(){animateZeah();},1000);
      } else {
        zeah++;
        zeahbomb = setTimeout(function(){animateZeah();},3000);
      }
    }
  });

  function animateZeah(){
    if(zeah==1){
      zeah++;
      zeahbomb = setTimeout(function(){animateZeah();},3000);
    } else if(zeah==2){
      $("#services img").hide().attr('src','https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/artboard/1/zeah-crouch.png');
    } else if(zeah==3){
      $("#services img").hide().attr('src','https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/artboard/1/zeah-lunge.png');
    }

    console.log('animate zeah: '+zeah);
    // to do: lazy load/load on scroll down
    // move zeah in from the left of the screen
  }
  $("#jikoeyes, .clickme3").click(function(){
    _gaq.push(['_trackEvent', 'home', 'click', 'swap eyes']);
    $("#jikoeyes").css('background-image','url('+rotateEyes()+')');
  });


  //<!-- mouth -->
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

    return "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/artboard/1/"+mouthsrc;
  }

  var chomptimeout;
  $("#jikomouth img").mouseleave(function(){
    // clear timer
    chomptimeout = clearInterval(chomptimeout);
    if($(this).hasClass('chomp')){
      $(this).removeClass('chomp');
    }
  });

  $("#jikomouth").click(function(){
    _gaq.push(['_trackEvent', 'home', 'click', 'swap mouth']);
    if(mouth == 0){
      chomptimeout = setInterval(function(){chomp()},100);
    }
    swapImage($("#jikomouth img"),rotateMouth());
  });

  //<!-- clothing -->
  var clothing = 0;
  function rotateClothes(){
    var clothing_sources = [
      "vampire-joe-jiko-smiling-noeyes.png",
      "crazy-joe-jiko-smiling-noeyes.png"
    ]
    if(clothing < 2) { clothing++; } else { clothing = 0; }
    switch(clothing){
      case 1:
        clothingsrc = "vampire-joe-jiko-smiling-noeyes.png";
        break;
      case 2:
        clothingsrc = "crazy-joe-jiko-smiling-noeyes.png";
        break;
      default:
        clothingsrc = "vampire-joe-jiko-smiling-noeyes-galaxy.png";
    }


    return "https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/artboard/1/"+clothingsrc;
//    $img.replaceWith('<img src="/images/public/about/'+clothingsrc+'">');
  }

  $("#jikoportrait img, .clickme").click(function(){
    _gaq.push(['_trackEvent', 'home', 'click', 'swap clothing']);
    if(clothing==2){
      clothing=0;
      $("#jikoportrait img").fadeOut('fast',function(){
        swapImage($("#jikoportrait img"),"/images/public/about/vampire-joe-jiko-smiling-noeyes-galaxy.png");
      });
    } else {
      swapImage($("#jikoportrait img"),rotateClothes());
      if(clothing==2){
        var to = setTimeout(function(){
          $("#jikoportrait img").trigger('click');
        },300);
      }
    }
  });

  $("#hints,.hints").mouseenter(function(){
    $('.click-map').stop(true,true).fadeIn(function(){
        _gaq.push(['_trackEvent', 'home', 'mouseenter', 'hints']);
    });
  }).mouseleave(function(){
    $('.click-map').stop(true,true).fadeOut();
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
//  fn_animateCloud();

  return {
    artboard: {
      info: "loaded"
    }
  };
});