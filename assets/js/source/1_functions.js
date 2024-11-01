
function wpqSetClass($) {
	$('.wprequal-400, .wprequal-600, .wprequal-800').removeClass('wprequal-400').removeClass('wprequal-600').removeClass('wprequal-800');
	$('.wprequal-form, .wprequal-calc').each(function() {
		var elementWidth = $(this).width();
		if ( 400 > elementWidth ) {
			$(this).addClass('wprequal-400');
		} else if ( 600 > elementWidth ) {
			$(this).addClass('wprequal-600');
		} else if ( 800 > elementWidth ) {
			$(this).addClass('wprequal-800');
		}else if ( 1000 > elementWidth ) {
			$(this).addClass('wprequal-1000');
		}
	});
}
