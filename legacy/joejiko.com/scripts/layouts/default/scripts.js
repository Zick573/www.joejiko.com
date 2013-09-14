// scroll body to 0px on click
$('document').ready(function(){
	console.log('zeah 0.11');
	var Footer = {
		zeah: {

			clicked: function(){
				var t = setTimeout(Footer.zeah.goingUp, 1000);
				$(this).find('span').html('Okay');
			},

			reset: function(){
				$('.zeah-accent-bubble span').html('Scroll to top, Human?');
			},

			goingUp: function(){
				$('.zeah-accent-bubble').find('span').html('Going up..');
				$('html, body').animate({
					scrollTop: 0
				}, 2000, Footer.zeah.reset());
			}
		}
	}

	$('.zeah-accent-bubble').on('click', function () {
		Footer.zeah.clicked.apply(this);
	});
});

// global template scripts
// hash redirect for old browsers. #!
	// not important
if(location.hash)
{
	// remove fragment #!/ -set pageurl -redirect
	var page_url = location.hash.slice(3);
	location.href = page_url;
}

function updatePage(params)
{
	document.title = params.title;
	if (typeof(history.replaceState) == 'function'){
		history.replaceState(null, params.title, params.url);
	} else {
		location.hash = '#!'+'/'+params.url;
	}

	_gaq.push(['_trackPageview', params.url]);
}