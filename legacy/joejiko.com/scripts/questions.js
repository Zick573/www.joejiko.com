$(document).ready(function(e){	
    var $sidebar   = $("aside.ask"),
        $window    = $(window),
        offset     = $sidebar.offset(),
        topPadding = 0;
		var $ask;

    $window.scroll(function() {
        if ($window.scrollTop() > offset.top) {
            $sidebar.stop().animate({
                marginTop: $window.scrollTop() - offset.top + topPadding
            });
        } else {
            $sidebar.stop().animate({
                marginTop: 0
            });
        }
    });

	
	$content = $(".question");
	$content.find('time').each(function(i){
		qtimeago = $.timeago($(this).html());
		$(this).empty().append("asked "+qtimeago);
	});
	
	$(document).on('cbox_closed', function(){
		// reset url
		updatePage({ title: 'Ask a stupid question, get a smart answer!',	url: '/questions'	});
	});
	$(document).on('cbox_complete', function(){
//		$('#tempOverlay').remove();
		$.colorbox.resize();
		// @todo move to cbox_loaded?
    $.getScript('/scripts/questions/ask.js');
	});

	$(document).on('click',"a.ask",function(){
		updatePage({ title: 'Ask me anything',	url: '/questions/ask'	});
		//          _trackEvent(category, action, opt_label, opt_value, opt_noninteraction)
		_gaq.push(['_trackEvent', 'questions', 'click', 'ask button']);
		//overlay
		$overlay = $('<div id="tempOverlay" style="background: #fff; height: 100%; position:fixed; width: 100%; position:absolute; left: 0; top: 0; z-index: 9999; display: block; opacity: 0.95; display: none;"></div>');
//		$('body').append($overlay);
//		$('#tempOverlay').fadeIn();
		$.get('/styles/questions/ask.css', function(css)
		{
			if(!$('style[data-name="ask"]').length)
			{
			 $('<style data-name="ask"></style>')
					.html(css)
					.appendTo("head");
			}
       $.colorbox({href: "/questions/ask", data: { "format" : "html" }, overlayClose: false, transition: 'fade'});
		});
/*
		$.getJSON("/questions/ask", function(data)
		{
			$('#tempOverlay').remove();
			$.colorbox({html: data.html, overlayClose: false, transition: 'fade'});
		});
*/
		return false;
	});	
	
	if($ask && $ask == "true"){	$('a.ask').trigger('click'); }
	
});