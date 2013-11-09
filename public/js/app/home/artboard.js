define(['jquery', 'jq/waypoints/jquery.waypoints.min', 'jq/timeago/jquery.timeago'], function($){
  // window load
  $("#artboard").fadeIn();
  // fn_loadGallery();
  $("#services img").waypoint(function(event, direction){
    console.log("zeah sees you");
    zeah++;
    animateZeah();
  },{triggerOnce:true,offset:'100%'});

  // var headlines = setTimeout(function(){
  //   $.getJSON('/api/questions',function(response){
  //     questionhtml = $("<p></p>");
  //     questionhtml.attr('id','recent-question');
  //     questionhtml.append("<h2>Ask me anything</h2>");
  //     questionhtml.append($(response.feed));
  //     questionhtml.hide();
  //     $("#status").append(questionhtml);
  //     $("#recent-question a").on('click',function(){
  //       return false;
  //     });
  //     var switchstatus = setTimeout(function(){
  //       $("#status p").fadeOut();
  //       qtimeago = $.timeago($("#recent-question .date time").html());
  //       $("#recent-question .date time").empty().append("asked "+qtimeago);
  //       $("#recent-question").fadeIn('slow');
  //     }, 3000);
  //   })
  // }, 3000);

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
    // _gaq.push(['_trackEvent', 'home', 'click', 'swap eyes']);
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
    // _gaq.push(['_trackEvent', 'home', 'click', 'swap mouth']);
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
    // _gaq.push(['_trackEvent', 'home', 'click', 'swap clothing']);
    if(clothing==2){
      clothing=0;
      $("#jikoportrait img").fadeOut('fast',function(){
        swapImage($("#jikoportrait img"),"https://googledrive.com/host/0B_9a_WMIXbTtNVhHd1J0WDZHd28/img/artboard/1/vampire-joe-jiko-smiling-noeyes-galaxy.png");
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
        // _gaq.push(['_trackEvent', 'home', 'mouseenter', 'hints']);
    });
  }).mouseleave(function(){
    $('.click-map').stop(true,true).fadeOut();
  });

  return {
    artboard: {
      info: "loaded"
    }
  };
});