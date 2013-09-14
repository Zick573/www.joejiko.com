$(document).ready(function(){
	$("#page-resume nav a").on('click', function(){
		$("html, body").animate({ scrollTop: $("#"+$(this).html()).position().top }, 600);
		return false;
	})

  var top = $('#page-resume aside').offset().top;
  var bottom = $('#page-resume aside').offset().bottom;
  var left = $('#page-resume aside').offset().left;
  $(window).on('resize', function(){
  	left = $('#page-resume aside').offset().left;
  	$('#page-resume nav.fixed').css('left', left);
  })
  $(window).scroll(function (event) {
    // what the y position of the scroll is
    var y = $(this).scrollTop();

    // whether that's below the form
    if (y >= top) {
      // if so, ad the fixed class
      $('#page-resume aside, #page-resume nav').addClass('fixed');
      $('#page-resume nav').css('left', left);
    } else {
      // otherwise remove it
      $('#page-resume aside, #page-resume nav').removeClass('fixed');
      $('#page-resume nav').css('left', 'auto');
    }
  });
});