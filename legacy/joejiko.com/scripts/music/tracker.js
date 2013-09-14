$(document).ready(function(){
	// initial page var
	var musicTracker = {
		lastfm : {
			user: null,
			total: {
				tracks: null,
				pages: null
			}
		},
		settings: {
			defaultUser: 'joejiko',
			page: null,
			realtime: false
		},
		spotify: {
			uri: null,
			request: null
		}
	};

	getLastfmUser();

	// open track in spotify when clicked
	$(document).on('click', '.spotify', function(event){
			s = musicTracker.spotify;
			s.uri = encodeURI($(this).attr("title"));
			s.request = "track";

			var errorTimeout = setTimeout(function() {
				alert("Spotify hates you. Try playing another song");
			}, 5000);

			$.getJSON("http://ws.spotify.com/search/1/"+s.request+".json?q="+s.uri,
				function(data, textStatus, xhr){
					clearTimeout(errorTimeout);

					console.log(textStatus);
					console.log(JSON.stringify(xhr));

					// direct to spotify link
					window.location = data.tracks[0].href;
			})
	});


	function getLastfmUser()
	{
		if($('#music-tracker select').length)
		{
			musicTracker.lastfm.user = $('#music-tracker select').val()
			return true;
		} else {
			musicTracker.lastfm.user = musicTracker.settings.defaultUser;
		}
	}

	function getLastfmJSON(page)
	{
		if(page){
			musicTracker.settings.page = page;
		} else { musicTracker.settings.page = $(".page-current").html(); }

		$.getJSON("/api/music/recent/"+musicTracker.lastfm.user+'/'+musicTracker.settings.page,
			function(data){
				$(".feed ul").empty();

				// parse JSON
				totalTracks = data.recenttracks["@attr"];
				musicTracker.lastfm.total.tracks = totalTracks.total;
				musicTracker.lastfm.total.pages = totalTracks.totalPages;

				$(".total-tracks span").html(musicTracker.lastfm.total.tracks);
				$(".pages-total").html(musicTracker.lastfm.total.pages);
				var tracks = [];
				$.each(data.recenttracks.track, function(i,track){

					if(track["@attr"]){
						tracks[i] = {
							img: track.image[1]["#text"],
							name: track.name,
							artist: track.artist["#text"],
							type: "now playing"
						};

					} else {

						tracks[i] =	{
							artist: track.artist["#text"],
							date: track.date["#text"],
							name: track.name,
							type: "track",
							url: track.url
						}

						if(track.streamable && track.streamable == "1"){
							//
							// TO DO: find out how to stream a preview
							//content += '<a href="'+track.url+'">play</a>';
						}
						//
						// TO DO: get the artist url by finding the _ and removing everything 1 character before it until the end
						//var artisturl = track.url;
					}

				});

				$.post('/music/view', {tracks:tracks},
					function(response){
						// expecting JSON track html
						$(".feed ul").remove();
						$(".feed").append(response.html);
						$(".feed li").fadeIn('slow',function(){
							if(!$('html').hasClass('mobile')){
								$('time').timeago();
								twttr.widgets.load();
							}
						});
						/* Last.fm timestamps are wonky >:[
						$timestamps =	$("#lastfm .timestamp");
						$.each($timestamps, function(i,v){
							timestamp =	$.timeago( $(v).html() )
							$(this).html(timestamp);
						});
						*/

					}, 'JSON');
			}
		);
	};

	function getRandomInt (min, max) {
			return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	function switchPage(action)
	{
		page = musicTracker.settings.page;

		switch(action)
		{

			case 'first':
			{
				if(page != 1)
				{
					page = 1;
				} else { return false; }
				break;
			}

			case 'next':
			{
				if( (page+1) <= musicTracker.lastfm.total.pages)
				{
					page++;
				} else { return false; }
				break;
			}

			case 'previous':
			{
				if( (page-1) > 0 )
				{
					page--;
				} else { return false; }
				break;
			}

			case 'last':
			{
				page = musicTracker.lastfm.total.pages;
				break;
			}

			case 'random':
				page = getRandomInt(1,musicTracker.lastfm.total.pages);
				break;
		}

		if(page != musicTracker.settings.page)
		{
			$('.page-current').html(page);
			getLastfmJSON(page);
		} else {
			getLastfmJSON();
		}

		return false;
	}

	$(".first").on('click', function(){ return switchPage('first'); });
	$(".previous").on('click', function(){ return switchPage('previous'); });
	$(".next").on('click', function(){ return switchPage('next'); });
	$(".last").on('click', function(){ return switchPage('last'); });
	$(".refresh").on('click', function(){ return switchPage('refresh'); });
	$(".random").on('click', function(){ return switchPage('random'); });

	var ajaxCount = 0;
	$('.feed').on('ajaxSend', function() {
		// dim
		ajaxCount++;
		$(this).addClass('dim');
		$('.feed ul').empty();
	}).on('ajaxComplete', function() {
		ajaxCount--;
		if(ajaxCount==0){
			$(this).removeClass('dim');
		}
	});

		// load first page
		if(musicTracker.settings.page == null)
		{
			initialize();
		}

		function initialize()
		{
			if($.trim( $('.page-current').html() ) != '')
			{
				musicTracker.settings.page = parseInt( $('.page-current').html() );
				$(".feed li").fadeIn('slow',function(){
					$('time').timeago();
				});
			} else {
				$(".first").trigger('click');
			}
		}

//
// TO DO: check last.fm for updates (start timer if "now playing" shows up when the page loads or a song has played in the past 3 minutes)
//	setTimeout("lastfmJSON()",3000);
    // do some stuff
		setInterval(function(){

		// only refresh if on page 1
		if(musicTracker.settings.page == 1 && musicTracker.settings.realtime == true) {
			$('.refresh').click();
		}

	},300000); // 5 minute refresh

!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=160372647359458";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
});

twttr.widgets.load();
// select lastfm user
/* unused
$('#musicTracker select').on("change", function() {
	musicTracker.lastfm.user = $(this).val();
	getLastfmJSON();
});*/

// scroll wrapper to top
/* unused
$("#feedwrap").animate({scrollTop: 0},'slow');*/